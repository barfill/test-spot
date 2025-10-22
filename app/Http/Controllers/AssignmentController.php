<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Dashboard;
use App\Models\AssignmentUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AssignmentRequest;

class AssignmentController extends Controller
{
    public function create($locale, Dashboard $dashboard)
    {
        $this->authorize('create', [Assignment::class, $dashboard]);

        return inertia('Assignment/Create', [
            'locale' => $locale,
            'dashboard' => $dashboard,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'image' => __('app.image'),
                'open' => __('app.open'),
                'closed' => __('app.closed'),
                'start_time' => __('app.start_time'),
                'end_time' => __('app.end_time'),
                'create' => __('app.create'),
                'create_dashboard' => __('app.create_dashboard'),
                'name' => __('app.name'),
                'description' => __('app.description'),
                'save' => __('app.save'),
                'cancel' => __('app.cancel'),
                'required' => __('validation.required'),
                'sign_in' => __('auth.sign_in'),
                'sign_out' => __('auth.sign_out'),
                'edit_profile' => __('app.edit_profile')
            ]
        ]);
    }

    public function store(AssignmentRequest $request, $locale, Dashboard $dashboard)
    {
        $this->authorize('create', [Assignment::class, $dashboard]);

        $validated = $request->validated();
        $validated['dashboard_id'] = $dashboard->id;
        Assignment::create($validated);

        return redirect()->route('dashboard.show', ['locale' => $locale, 'dashboard' => $dashboard->id])
            ->with('success', $this->successMessage('create', 'assignment'));
    }

    public function show($locale, Dashboard $dashboard, Assignment $assignment)
    {
        $user = Auth::user();

        $isOwner = $dashboard->created_by === $user->id;
        $isMember = $dashboard->users()->where('user_id', $user->id)->exists();

        if (!$isOwner && !$isMember) {
            abort(403, 'Access denied to this dashboard');
        }

        // if ($user->type === 'student' && $isMember) {
        if ($isMember && $assignment->status === 'open') {
            return $this->showForStudent($locale, $dashboard, $assignment);
        }

        if ($user->type === 'teacher' && $isOwner && $assignment->status === 'open') {
            return $this->showForTeacher($locale, $dashboard, $assignment);
        }

        abort(403, 'Insufficient permissions');
    }

    private function showForStudent($locale, Dashboard $dashboard, Assignment $assignment)
    {
        $user = Auth::user();

        $assignmentUser = AssignmentUser::firstOrCreate(
            [
                'assignment_id' => $assignment->id,
                'user_id' => $user->id,
            ],
            [
                'status' => 'in_progress'
            ]
        );

        return inertia('Assignment/StudentShow', [
            'locale' => $locale,
            'dashboard' => $dashboard,
            'assignment' => $assignment,
            'assignmentUser' => $assignmentUser,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'assignment' => __('app.assignment'),
                'submit_assignment' => __('app.submit_assignment'),
                'submitting' => __('app.submitting'),
                'add_file' => __('app.add_file'),
                'file_upload' => __('app.file_upload'),
                'add_file' => __('app.add_file'),
                'choose_file' => __('app.choose_file'),
                'accepted_formats' => __('app.accepted_formats'),
                'status' => __('app.status'),
                'grade' => __('app.grade'),
                'comment' => __('app.comment'),
                'optional' => __('app.optional'),
                'user_comment' => __('app.user_comment'),
                'submitted_at' => __('app.submitted_at'),
                'submit' => __('app.submit'),
                'back' => __('app.back'),
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

    private function showForTeacher($locale, Dashboard $dashboard, Assignment $assignment)
    {
        $userPendingAssignments = AssignmentUser::with('user', 'assignment')
            ->where('assignment_id', $assignment->id)
            ->where('status', 'pending')
            ->get()
            ->keyBy('submitted_at');

        // dd($userPendingAssignments);

        return inertia('Assignment/TeacherShow', [
            'locale' => $locale,
            'dashboard' => $dashboard,
            'assignment' => $assignment,
            'userAssignments' => $userPendingAssignments,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'assignment' => __('app.assignment'),
                'students' => __('app.students'),
                'submissions' => __('app.submissions'),
                'status' => __('app.status'),
                'grade' => __('app.grade'),
                'plagiarism_check' => __('app.plagiarism_check'),
                'compilation_check' => __('app.compilation_check'),
                'edge_cases_check' => __('app.edge_cases_check'),
                'mark_all_passed' => __('app.mark_all_passed'),
                'back' => __('app.back'),
                'submitted' => __('app.submitted'),
                'plagiarism_check' => __('app.plagiarism_check'),
                'compilation_check' => __('app.compilation_check'),
                'edge_cases_check' => __('app.edge_cases_check'),
                'test_passed' => __('app.test_passed'),
                'test_failed' => __('app.test_failed'),
                'test_not_run' => __('app.test_not_run'),
            ]
        ]);
    }


    public function edit($locale, Dashboard $dashboard, Assignment $assignment)
    {
        $this->authorize('update', [$dashboard, $assignment]);
        return inertia('Assignment/Edit', [
            'locale' => $locale,
            'dashboard' => $dashboard,
            'assignment' => [
                'id' => $assignment->id,
                'name' => $assignment->name,
                'description' => $assignment->description,
                'start_time' => $assignment->start_time?->format('Y-m-d\TH:i'),
                'end_time' => $assignment->end_time?->format('Y-m-d\TH:i'),
                'status' => $assignment->status,
            ],
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'image' => __('app.image'),
                'open' => __('app.open'),
                'closed' => __('app.closed'),
                'start_time' => __('app.start_time'),
                'end_time' => __('app.end_time'),
                'create' => __('app.create'),
                'update' => __('app.update'),
                'edit' => __('app.edit'),
                'create_dashboard' => __('app.create_dashboard'),
                'create_assignment' => __('app.create_assignment'),
                'name' => __('app.name'),
                'description' => __('app.description'),
                'save' => __('app.save'),
                'cancel' => __('app.cancel'),
                'required' => __('validation.required'),
                'sign_in' => __('auth.sign_in'),
                'sign_out' => __('auth.sign_out'),
                'edit_profile' => __('app.edit_profile')
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AssignmentRequest $request, $locale, Dashboard $dashboard, Assignment $assignment)
    {
        $this->authorize('update', [$dashboard, $assignment]);

        $validated = $request->validated();

        $assignment->update($validated);

        return redirect()->route('dashboard.show', ['locale' => $locale, 'dashboard' => $dashboard->id])
            ->with('success', $this->successMessage('update', 'assignment'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($locale, Dashboard $dashboard,Assignment $assignment)
    {
        $this->authorize('delete', [$dashboard, $assignment]);

        $assignment->delete();

         return redirect()->route('dashboard.show', ['locale' => $locale, 'dashboard' => $dashboard->id])
            ->with('success', $this->successMessage('delete', 'assignment'));
    }

    private function successMessage($action, $attribute) {
        return __("messages.assignment_".$action."_success", ['attribute' => __("app.$attribute")]);
    }


    public function submit(Request $request, $locale, Dashboard $dashboard, Assignment $assignment)
    {
        $request->validate([
            'code_file' => 'required|file|extensions:cpp,cc,cxx,c,h,hpp|max:5120|min:1',
            'user_comment' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        $assignmentUser = AssignmentUser::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$assignmentUser) {
            abort(404, 'Assignment not found for this user');
        }

        if ($assignmentUser->file_path && Storage::exists($assignmentUser->file_path)) {
            Storage::delete($assignmentUser->file_path);
        }

        $fileName = time() . '_' . $request->file('code_file')->getClientOriginalName();
        $path = $request->file('code_file')->storeAs("submissions/{$assignment->id}/{$user->id}", $fileName);

        $assignmentUser->update([
            'file_path' => $path,
            'user_comment' => $request->user_comment,
            'status' => 'pending',
            'submitted_at' => now(),
            'compilation_check_result' => null,
            'plagiarism_check_result' => null,
            'edge_cases_check_result' => null,
            'grade' => null,
            'review_comment' => null,
        ]);

        return redirect()->route('dashboard.assignments.show', [
            'locale' => $locale,
            'dashboard' => $dashboard->id,
            'assignment' => $assignment->id
        ])->with('success', __('messages.submission_success'));
    }
}
