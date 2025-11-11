<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;
use App\Models\Assignment;
use App\Models\AssignmentUser;
use Illuminate\Support\Facades\Storage;
use App\Services\CompilationService;
use App\Services\TestCasesService;
use App\Services\ReportGeneratorService;
use Barryvdh\DomPDF\Facade\Pdf;

class AssignmentUserController extends Controller
{
    public function show($locale, Dashboard $dashboard, Assignment $assignment, AssignmentUser $assignmentUser)
    {
        $this->authorize('update', [Assignment::class, $dashboard, $assignment]);

        $assignmentUser->load('user');

        $fileContent = null;
        if ($assignmentUser->file_path && Storage::exists($assignmentUser->file_path)) {
            $fileContent = Storage::get($assignmentUser->file_path);
        }

        return inertia('Assignment/User/Show', [
            'locale' => $locale,
            'dashboard' => $dashboard,
            'assignment' => $assignment,
            'assignmentUser' => $assignmentUser,
            'fileContent' => $fileContent,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'assignment' => __('app.assignment'),
                'assignment_description' => __('app.assignment_description'),
                'assignment_file' => __('app.assignment_file'),
                'submit_assignment' => __('app.submit_assignment'),
                'student_comment' => __('app.student_comment'),
                'assignment_assessment' => __('app.assignment_assessment'),
                'submitting' => __('app.submitting'),
                'code_file' => __('app.code_file'),
                'file_content' => __('app.file_content'),
                'no_file'  => __('app.no_file'),
                'add_file' => __('app.add_file'),
                'file_upload' => __('app.file_upload'),
                'add_file' => __('app.add_file'),
                'plagiarism_check' => __('app.plagiarism_check'),
                'compilation_check' => __('app.compilation_check'),
                'test_cases_check' => __('app.test_cases_check'),
                'plagiarism_check_error' => __('app.plagiarism_check_error'),
                'compilation_check_error' => __('app.compilation_check_error'),
                'test_cases_check_error' => __('app.test_cases_check_error'),
                'random_cases_check' => __('app.random_cases_check'),
                'edge_cases_check' => __('app.edge_cases_check'),
                'no_items' => __('app.no_items'),
                'download_file' => __('app.download_file'),
                'dashboards_e' => __('app.dashboards_e'),
                'assignments_e' => __('app.assignments_e'),
                'errors_e' => __('app.errors_e'),
                'Errors_e' => __('app.Errors_e'),
                'choose_file' => __('app.choose_file'),
                'accepted_formats' => __('app.accepted_formats'),
                'status' => __('app.status'),
                'grade' => __('app.grade'),
                'select_grade' => __('app.select_grade'),
                'comment' => __('app.comment'),
                'optional' => __('app.optional'),
                'user_comment' => __('app.user_comment'),
                'submitted' => __('app.submitted'),
                'submit' => __('app.submit'),
                'back' => __('app.back'),
                'email' => __('app.email'),
                'cancel' => __('app.cancel'),
                'contact_teacher' => __('app.contact_teacher'),
                'assignment_reviewing' => __('app.assignment_reviewing'),
                'review_results' => __('app.review_results'),
                'assignment_status' => __('app.assignment_status'),
                'status_completed' => __('app.status_completed'),
                'status_failed' => __('app.status_failed'),
                'review_comment' => __('app.review_comment'),
                'test_passed' => __('app.test_passed'),
                'test_failed' => __('app.test_failed'),
                'test_not_run' => __('app.test_not_run'),
                'test_in_progress' => __('app.test_in_progress'),
                'for' => __('app.for'),
                'generate_ai_report' => __('app.generate_ai_report'),
                'ai_report' => __('app.ai_report'),
                'ai_report_generated' => __('app.ai_report_generated'),
                'student' => __('app.student'),
                'name' => __('app.name'),
                'assignment' => __('app.assignment'),
                'summary' => __('app.summary'),
                'recommended_grade' => __('app.recommended_grade'),
                'N/A' => __('app.N/A'),
            ]
        ]);
    }

    public function update(Request $request, $locale, Dashboard $dashboard, Assignment $assignment, AssignmentUser $assignmentUser)
    {
        $this->authorize('update', [Assignment::class, $dashboard, $assignment]);

        $request->validate([
            'grade' => 'required|integer|min:2|max:5',
            'review_comment' => 'nullable|string|max:1000',
        ]);

        if ($assignmentUser->status !== 'pending') {
            return redirect()->back()->with('error', 'This submission cannot be graded. Current status: ' . $assignmentUser->status);
        }

        $grade = $request->input('grade');
        $review_comment = $request->input('review_comment');
        $graded_status = ($grade >= 3) && ($grade <= 5) ? 'graded_passed' : 'graded_failed';

        if ($dashboard->users()->where('user_id', $assignmentUser->user_id)->doesntExist()) {
            return redirect()->back()->with('error', 'Assignment user does not belong to the dashboard.');
        }

        $assignmentUser->update([
            'grade' => $grade,
            'review_comment' => $review_comment,
            'status' => $graded_status,
        ]);

        return redirect()->route('dashboard.assignments.show', [
            'locale' => $locale,
            'dashboard' => $dashboard->id,
            'assignment' => $assignment->id
        ])->with('success', 'Assignment graded successfully.');
    }

    public function compileSubmission($locale, Dashboard $dashboard, Assignment $assignment, AssignmentUser $assignmentUser, CompilationService $compilationService)
    {
        $this->authorize('update', [$dashboard, $assignment]);

        $results = $compilationService->compileSingleSubmission($assignmentUser);

        return response()->json([
            'success' => true,
            'message' => 'Compilation completed',
            'results' => $results
        ]);
    }

    public function testRandom($locale, Dashboard $dashboard, Assignment $assignment, AssignmentUser $assignmentUser, TestCasesService $testCasesService)
    {
        // $this->authorize('update', [$dashboard, $assignment]);

        $results = $testCasesService->analyzeCode($assignmentUser, 'random');

        return response()->json([
            'success' => true,
            'message' => 'Random test cases executed',
            'results' => $results
        ]);
    }

    public function testEdge($locale, Dashboard $dashboard, Assignment $assignment, AssignmentUser $assignmentUser, TestCasesService $testCasesService)
    {
        // $this->authorize('update', [$dashboard, $assignment]);

        $results = $testCasesService->analyzeCode($assignmentUser, 'edge');

        return response()->json([
            'success' => true,
            'message' => 'Edge test cases executed',
            'results' => $results
        ]);
    }

    public function generateAiReport($locale, Dashboard $dashboard, Assignment $assignment, AssignmentUser $assignmentUser, ReportGeneratorService $reportGeneratorService)
    {
        // $this->authorize('update', [$dashboard, $assignment]);

        $results = $reportGeneratorService->generateReport($locale, $assignmentUser);

        $assignmentUser->load('user');

        $pdf = Pdf::loadView('pdf.ai-report', [
            'report' => $results,
            'student' => $assignmentUser->user,
            'assignment' => $assignment,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $date = now()->format('Y-m-d');
        $filename = "{$date}_AI_Report_{$assignmentUser->user->last_name}.pdf";

        return $pdf->download($filename);
    }
}
