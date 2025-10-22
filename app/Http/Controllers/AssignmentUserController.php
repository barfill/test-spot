<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;
use App\Models\Assignment;
use App\Models\AssignmentUser;
use Illuminate\Support\Facades\Storage;

class AssignmentUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($locale, Dashboard $dashboard, Assignment $assignment, AssignmentUser $assignmentUser)
    {
        $this->authorize('update', [Assignment::class, $dashboard, $assignment]);

        $assignmentUser->load('user');

        $fileContent = null;
        if ($assignmentUser->file_path && Storage::exists($assignmentUser->file_path)) {
            $fileContent = Storage::get($assignmentUser->file_path);
            // dd($fileContent);
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
                'edge_cases_check' => __('app.edge_cases_check'),
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
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
