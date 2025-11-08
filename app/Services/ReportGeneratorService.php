<?php

namespace App\Services;

use App\AiAgents\ReportGeneratorAgent;
use App\Models\Assignment;
use App\Models\AssignmentUser;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;

class ReportGeneratorService
{
    public function generateReport(AssignmentUser $assignmentUser): array
    {
        $code = Storage::get($assignmentUser->file_path);
        $agent = ReportGeneratorAgent::for('report_generation');

        $plagiarismResult = json_encode($assignmentUser->plagiarism_check_result ?? ['message' => 'No plagiarism check performed'], JSON_PRETTY_PRINT);
        $compilationResult = json_encode($assignmentUser->compilation_check_result ?? ['message' => 'No compilation check performed'], JSON_PRETTY_PRINT);
        $testCasesResult = json_encode($assignmentUser->test_cases_results ?? ['message' => 'No test cases executed'], JSON_PRETTY_PRINT);

        $prompt = "
            Kod studenta:
            ```cpp
            {$code}
            ```

            Wynik sprawdzenia plagiatu:
            {$plagiarismResult}

            Wynik kompilacji:
            {$compilationResult}

            Wyniki testów:
            {$testCasesResult}

            Na podstawie powyższych informacji wygeneruj raport oceniający pracę studenta,
            uwzględniając jakość kodu, wyniki testów oraz ewentualne problemy z plagiatem i kompilacją.
        ";

        $response = $agent->respond($prompt);

        if (!$response) {
            Log::error('Empty AI response', [
                'assignment_user_id' => $assignmentUser->id
            ]);

            return [
                'success' => false,
                'error' => 'Empty AI response',
                'report' => []
            ];
        }

        $jsonMatch = preg_match('/\{.*\}/s', $response, $matches);
        $jsonString = $jsonMatch ? $matches[0] : $response;
        $responseJson = json_decode($jsonString, true);

        if (!$responseJson) {
            Log::error('Failed to parse AI response', [
                'response' => $response,
                'assignment_user_id' => $assignmentUser->id
            ]);

            return [
                'success' => false,
                'error' => 'Failed to parse AI response',
                'report' => []
            ];
        }

        $assignmentUser->update([
            'ai_generated_report' => $responseJson
        ]);

        return $responseJson;
    }

}
