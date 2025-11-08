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
//     public function analyzeCode(AssignmentUser $assignmentUser, string $type):array
//     {
//         $assignment = $assignmentUser->assignment;
//         $code = Storage::get($assignmentUser->file_path);

//         $agent = TestCasesAgent::for('plagiarism_check');
//         $prompt = "
// Opis zadania:
// {$assignment->description}

// Typ testu: {$type}

// Kod studenta do przeanalizowania:
// ```cpp
// {$code}
// ```

// WAŻNE INSTRUKCJE:
// 1. Przeanalizuj dokładnie KOD STUDENTA (powyżej) i zrozum co on robi
// 2. Wygeneruj " . ($type === 'random' ? '3-5 losowych' : '3-5 brzegowych') . " przypadków testowych
// 3. Dla każdego przypadku:
//    - Określ konkretne dane wejściowe (input) - np. \"5 3\" lub \"0 0\"
//    - Przewidź co kod studenta FAKTYCZNIE zwróci (actual_output) - przeanalizuj kod linijka po linijce
//    - Określ co POWINIEN zwrócić zgodnie z opisem zadania (expected_output)
//    - Porównaj actual_output z expected_output i ustaw passed na true/false

// 4. NIE PISZ OPISÓW typu \"program zwróci wynik\" - PODAJ KONKRETNE WARTOŚCI!

// Przykład DOBREJ odpowiedzi:
// {
//   \"test_type\": \"random\",
//   \"success\": false,
//   \"test_cases\": [
//     {
//       \"input\": \"5 3\",
//       \"expected_output\": \"8\",
//       \"actual_output\": \"8\",
//       \"passed\": true,
//       \"description\": \"Podstawowe dodawanie\"
//     },
//     {
//       \"input\": \"0 0\",
//       \"expected_output\": \"0\",
//       \"actual_output\": \"error: division by zero\",
//       \"passed\": false,
//       \"description\": \"Test z zerami\"
//     }
//   ]
// }

// Przeanalizuj kod i wygeneruj przypadki testowe w formacie JSON.
// ";
//         $response = $agent->respond($prompt);

//         // Wyciągnij JSON z odpowiedzi (może być opakowany w markdown)
//         $jsonMatch = preg_match('/\{.*\}/s', $response, $matches);
//         $jsonString = $jsonMatch ? $matches[0] : $response;

//         $responseJson = json_decode($jsonString, true);

//         // Jeśli parsowanie się nie powiodło, zwróć błąd
//         if (!$responseJson || !isset($responseJson['test_type'])) {
//             Log::error('Failed to parse AI response', [
//                 'response' => $response,
//                 'assignment_user_id' => $assignmentUser->id
//             ]);

//             return [
//                 'test_type' => $type,
//                 'success' => false,
//                 'error' => 'Failed to parse AI response',
//                 'test_cases' => []
//             ];
//         }

//         // Przelicz success na podstawie test_cases
//         $allPassed = true;
//         if (isset($responseJson['test_cases']) && is_array($responseJson['test_cases'])) {
//             foreach ($responseJson['test_cases'] as $testCase) {
//                 if (isset($testCase['passed']) && $testCase['passed'] === false) {
//                     $allPassed = false;
//                     break;
//                 }
//             }
//         }

//         // Nadpisz success obliczonym na podstawie testów
//         $responseJson['success'] = $allPassed;

//         $currentResults = $assignmentUser->test_cases_results ?? [];
//         $currentResults[$type] = $responseJson;

//         $assignmentUser->update([
//             'test_cases_results' => $currentResults
//         ]);

//         return $responseJson;
//     }

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
