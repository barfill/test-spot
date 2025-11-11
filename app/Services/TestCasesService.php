<?php

namespace App\Services;

use App\AiAgents\TestCasesAgent;
use App\Models\Assignment;
use App\Models\AssignmentUser;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;

class TestCasesService
{
    public function analyzeCode(AssignmentUser $assignmentUser, string $type):array
    {
        $assignment = $assignmentUser->assignment;
        $code = Storage::get($assignmentUser->file_path);

        $agent = TestCasesAgent::for('plagiarism_check');
        $prompt = "
            Opis zadania:
            \"{$assignment->description}\"

            Typ testu: {$type}

            Kod studenta do przeanalizowania:
            ```cpp
            {$code}
            ```
            ";

        $response = $agent->respond($prompt);

        if (!$response) {
            Log::error('Empty AI response', [
                'assignment_user_id' => $assignmentUser->id
            ]);

            return [
                'test_type' => $type,
                'success' => false,
                'error' => 'Empty AI response',
                'test_cases' => []
            ];
        }

        $jsonMatch = preg_match('/\{.*\}/s', $response, $matches);
        $jsonString = $jsonMatch ? $matches[0] : $response;
        $responseJson = json_decode($jsonString, true);

        if (!$responseJson || !isset($responseJson['test_type']) || !in_array($responseJson['test_type'], ['random', 'edge'])) {
            Log::error('Failed to parse AI response', [
                'response' => $response,
                'assignment_user_id' => $assignmentUser->id
            ]);

            return [
                'test_type' => $type,
                'success' => false,
                'error' => 'Failed to parse AI response',
                'test_cases' => []
            ];
        }

        $allPassed = true;
        if (isset($responseJson['test_cases']) && is_array($responseJson['test_cases'])) {
            foreach ($responseJson['test_cases'] as $testCase) {
                if (isset($testCase['passed']) && $testCase['passed'] === false) {
                    $allPassed = false;
                    break;
                }
            }
        }

        $responseJson['success'] = $allPassed;

        $currentResults = $assignmentUser->test_cases_results ?? [];
        $currentResults['general_success'] = $currentResults['general_success'] ?? null;
        $currentResults['tests'][$type] = $responseJson;

        $random = $currentResults['tests']['random']['success'] ?? null;
        $edge = $currentResults['tests']['edge']['success'] ?? null;

        if ($random === true && $edge === true) {
            $currentResults['general_success'] = true;
        } elseif ($random === false && $edge === false) {
            $currentResults['general_success'] = false;
        } elseif ($random !== null || $edge !== null) {
            $currentResults['general_success'] = 'partial';
        } else {
            $currentResults['general_success'] = $random ?? $edge ?? null;
        }

        $assignmentUser->update([
            'test_cases_results' => $currentResults
        ]);

        return $responseJson;
    }
}
