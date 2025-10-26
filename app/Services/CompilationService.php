<?php

namespace App\Services;

use App\Models\Assignment;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use App\Models\AssignmentUser;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CompilationService
{
    private const TIMEOUT_SECONDS = 60;

    public function compileUserAssignment(AssignmentUser $assignmentUser): array
    {
        try {
            if (!$assignmentUser->file_path) {
                throw new Exception('No file path for assignment user ID ' . $assignmentUser->id);
            }

            if (!Storage::exists($assignmentUser->file_path)) {
                throw new Exception('File does not exist at path: ' . $assignmentUser->file_path);
            }

            $filePath = Storage::path($assignmentUser->file_path);
            $outputPath = sys_get_temp_dir() . '/compiled_' . uniqid();

            $compilerCommand = ['g++', '-std=c++17', '-o', $outputPath, $filePath];
            $compilerName = 'g++';

            $process = new Process($compilerCommand);
            $process->setTimeout(self::TIMEOUT_SECONDS);

            try {
                $process->run();

                if ($process->isSuccessful()) {
                    $result = $this->createSuccessResult(
                        $process->getOutput(),
                        $compilerName
                    );
                } else {
                    $result = $this->createFailureResult(
                        $process->getErrorOutput(),
                        'compilation_error',
                        $compilerName
                    );
                }

            } catch ( ProcessTimedOutException $e) {
                Log::error('Compilation timed out', [
                    'assignment_user_id' => $assignmentUser->id,
                    'user_id' => $assignmentUser->user_id,
                    'error' => $e->getMessage(),
                ]);

                $result = $this->createFailureResult(
                        $process->getErrorOutput(),
                        'timeout',
                        $compilerName
                );
            }

            if (file_exists($outputPath)) {
                unlink($outputPath);
            }

            return $result;

        } catch ( Exception $e) {
            Log::error('Compilation failed', [
                'assignment_user_id' => $assignmentUser->id,
                'user_id' => $assignmentUser->user_id,
                'error' => $e->getMessage(),
            ]);

            return $this->createFailureResult(
                        'Internal error during compilation: ' . $e->getMessage(),
                        'error'
            );
        }

    }

    public function compileAllSubmissions(Assignment $assignment): array
    {
        $assignmentUsers = $assignment->assignmentUsers()
            ->whereNotNull('file_path')
            ->where('status', 'pending')
            ->get();

        $results = [
            'total' => $assignmentUsers->count(),
            'successful' => 0,
            'failed' => 0,
            'processed' => [],
        ];

        foreach ($assignmentUsers as $assignmentUser) {
            try {
                $compilationResult = $this->compileUserAssignment($assignmentUser);

                $assignmentUser->update([
                    'compilation_check_result' => $compilationResult
                ]);

                if ($compilationResult['success']) {
                    $results['successful']++;
                } else {
                    $results['failed']++;
                }

                $results['processed'][] = [
                    'assignment_user_id' => $assignmentUser->id,
                    'user_name' => $assignmentUser->user->first_name . ' ' . $assignmentUser->user->last_name,
                    'success' => $compilationResult['success'],
                    'status' => $compilationResult['status'],
                ];
            } catch (Exception $e) {
                Log::error('Compilation failed for user assignment', [
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

    public function compileSingleSubmission(AssignmentUser $assignmentUser): array
    {
        $results = [
            'total' => 1,
            'successful' => 0,
            'failed' => 0,
            'processed' => [],
        ];

        try {
                $compilationResult = $this->compileUserAssignment($assignmentUser);

                $assignmentUser->update([
                    'compilation_check_result' => $compilationResult
                ]);

                if ($compilationResult['success']) {
                    $results['successful']++;
                } else {
                    $results['failed']++;
                }

                $results['processed'][] = [
                    'assignment_user_id' => $assignmentUser->id,
                    'user_name' => $assignmentUser->user->first_name . ' ' . $assignmentUser->user->last_name,
                    'success' => $compilationResult['success'],
                    'status' => $compilationResult['status'],
                ];
            } catch (Exception $e) {
                Log::error('Compilation failed for user assignment', [
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

         return $results;
    }

    private function createSuccessResult(string $output, string $compiler): array
    {
        return [
            'success' => true,
            'status' => 'success',
            'output' => $output ?: 'Compilation successful',
            'errors' => null,
            'compiler' => $compiler,
            'checked_at' => now()->toIso8601String(),
        ];
    }

    private function createFailureResult(string $errors, string $status, ?string $compiler = null): array
    {
        return [
            'success' => false,
            'status' => $status,
            'output' => null,
            'errors' => $errors,
            'compiler' => $compiler,
            'checked_at' => now()->toIso8601String(),
        ];
    }
}
