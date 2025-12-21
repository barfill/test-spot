<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{

    public function index($locale)
    {
        $this->authorize('viewAny', User::class);

        $users = User::allUsers()->get();

        return inertia('User/Index', [
            'users' => $users,
            'locale' => $locale,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'users' => __('app.users'),
                'search_users' => __('app.search_users'),
                'email' => __('app.email'),
                'type' => __('app.type'),
                'edit' => __('app.edit'),
                'delete' => __('app.delete'),
                'create_user' => __('app.create_user'),
                'sign_in' => __('auth.sign_in'),
                'sign_out' => __('auth.sign_out'),
                'edit_profile' => __('app.edit_profile')
            ]
        ]);
    }

    public function create($locale)
    {
        $this->authorize('create', User::class);

        return inertia('User/Create', [
            'locale' => $locale,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'users' => __('app.users'),
                'create_user' => __('app.create_user'),
                'email' => __('app.email'),
                'password' => __('app.password'),
                'type' => __('app.type'),
                'title' => __('app.title'),
                'first_name' => __('app.first_name'),
                'last_name' => __('app.last_name'),
                'student' => __('app.student'),
                'teacher' => __('app.teacher'),
                'admin' => __('app.admin'),
                'save' => __('app.save'),
                'cancel' => __('app.cancel'),
                'sign_in' => __('auth.sign_in'),
                'sign_out' => __('auth.sign_out'),
                'edit_profile' => __('app.edit_profile')
            ]
        ]);
    }

    public function store(UserRequest $request, $locale)
    {
         $this->authorize('create', User::class);

        $validated = $request->validated();

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('user.index', ['locale' => $locale])
            ->with('success', $this->successMessage('create', 'user'));
    }


    public function show($locale, User $user)
    {
        $this->authorize('view', $user);

        return inertia('User/Show', [
            'editedUser' => $user,
            'locale' => $locale,
            'translations' => [
                'dashboards' => __('app.dashboards'),
                'users' => __('app.users'),
                'edit_user' => __('app.edit_user'),
                'email' => __('app.email'),
                'password' => __('app.password'),
                'type' => __('app.type'),
                'title' => __('app.title'),
                'first_name' => __('app.first_name'),
                'last_name' => __('app.last_name'),
                'student' => __('app.student'),
                'teacher' => __('app.teacher'),
                'admin' => __('app.admin'),
                'save' => __('app.save'),
                'cancel' => __('app.cancel'),
                'sign_in' => __('auth.sign_in'),
                'sign_out' => __('auth.sign_out'),
                'edit_profile' => __('app.edit_profile'),
                'leave_empty_to_keep_current_password' => __('messages.leave_empty_to_keep_current_password')
            ]
        ]);
    }


    public function update(UserRequest $request, $locale, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validated();

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        if ($user->type !== 'admin') {
            return redirect()->route('dashboard.index', ['locale' => $locale])
            ->with('success', $this->successMessage('update', 'user'));
        }

        return redirect()->route('user.index', ['locale' => $locale])
            ->with('success', $this->successMessage('update', 'user'));
    }

    public function destroy($locale, User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('user.index', ['locale' => $locale])->with('success', $this->successMessage('delete', 'user'));
    }

    private function successMessage($action, $attribute) {
        return __("messages.$action"."_success", ['attribute' => __("app.$attribute")]);
    }
}
