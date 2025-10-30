<?php

namespace App\Services;

use App\AiAgents\PlagiarismAgent;
use App\Models\Assignment;
use App\Models\AssignmentUser;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;

class PlagiarismService
{
    public function checkAssignment(Assignment $assignment):array
    {
        $assignmentUsers = $assignment->assignmentUsers()
            ->whereNotNull('file_path')
            ->where('status', 'pending')
            ->get();

        $results = [
            'total' => $assignmentUsers->count(),
            'suspicious' => 0,
            'clean' => 0,
            'successful' => 0,
            'failed' => 0,
            'processed' => [],

        ];

        $seenPairs = [];

        foreach ($assignmentUsers as $assignmentUser) {
            try {
                $plagiarismCheckResult = $this->checkSubmission($assignmentUser, $assignmentUsers, $seenPairs);

                $assignmentUser->update([
                    'plagiarism_check_result' => $plagiarismCheckResult
                ]);

                $results['successful']++;
                $results['processed'][] = [
                    'assignment_user_id' => $assignmentUser->id,
                    'user_name' => $assignmentUser->user->first_name . ' ' . $assignmentUser->user->last_name,
                    'success' => true,
                    'status' => $plagiarismCheckResult['status'],
                ];

                if ($plagiarismCheckResult['status'] === 'suspicious') {
                    $results['suspicious']++;
                } else {
                    $results['clean']++;
                }
            } catch (Exception $e) {
                Log::error('Plagiarism check failed for user assignment', [
                    'assignment_user_id' => $assignmentUser->id,
                    'user_id' => $assignmentUser->user_id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                $results['failed']++;
                $results['processed'][] = [
                    'assignment_user_id' => $assignmentUser->id,
                    'user_name' => $assignmentUser->user->first_name . ' ' . $assignmentUser->user->last_name,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Exception: ' . $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    private function checkSubmission(AssignmentUser $current, $other, array &$seenPairs = []): array
    {
        $currentCodeRaw = Storage::get($current->file_path);
        $currentCode = $this->normalizeCode($currentCodeRaw);
        $currentUserId = $current->user_id;

        $matches = [];
        $matches[$currentUserId] = [];

        foreach ($other as $otherSubmission) {
            $otherUserId = $otherSubmission->user_id;

            if ($currentUserId === $otherUserId) {
                continue;
            }

            $pairKey = min($currentUserId, $otherUserId) . '-' . max($currentUserId, $otherUserId);

            if (isset($seenPairs[$pairKey])) {
                continue;
            }
            $seenPairs[$pairKey] = true;

            $otherCodeRaw = Storage::get($otherSubmission->file_path);
            $otherCode = $this->normalizeCode($otherCodeRaw);

            usleep(150_000);

            $aiCheckResult = $this->checkPlagiarismWithAgent(
                $currentUserId,
                $currentCode,
                $otherUserId,
                $otherCode
            );

            $matches[$currentUserId][] = $aiCheckResult;
        }

        return [
            'success' => true,
            'status' => count($matches) > 0 ? 'suspicious' : 'clean',
            'matches' => $matches,
            'total_comparisons' => count($other) - 1,
        ];
    }

    private function checkPlagiarismWithAgent($userId1, $code1, $userId2, $code2): array
    {
        $agent = PlagiarismAgent::for('plagiarism_check');
        $prompt = $this->createPrompt($userId1, $code1, $userId2, $code2);
        $response = $agent->respond(($prompt));

        $responseJson = preg_match('/\{.*?\}/s', $response, $matches) ? $matches[0] : null;
        if ($responseJson) {
            return json_decode($responseJson, true);
        }

        return [
            'comparison_user_id' => $userId2,
            'similarity_score' => $responseJson['similarity_score'] ?? 0,
            'is_plagiarism' => $responseJson['is_plagiarism'] ?? false,
            'details' => $responseJson['details'] ?? 'Brak szczegółów w odpowiedzi agenta.',
        ];
    }

    private function createPrompt($userId1, $code1, $userId2, $code2)
    {
        return "Analizuj te dwa przesłane kody C++ pod kątem plagiatu.
        (Użytkownik 1: $userId1, Kod 1: $code1), (Użytkownik 2: $userId2, Kod 2: $code2)";
    }

    private function normalizeCode($code)
    {
        $code = preg_replace('/\/\/.*$/m', '', $code);
        $code = preg_replace('/\/\*.*?\*\//s', '', $code);
        $code = preg_replace('/\s+/', ' ', $code);
        return trim($code);
    }
}
