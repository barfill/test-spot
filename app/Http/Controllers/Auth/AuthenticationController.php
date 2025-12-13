<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function create($locale)
    {
        return inertia('Auth/Create', [
            'locale' => $locale,
            'translations' => [
                'login' => __('auth.login'),
                'email' => __('auth.email'),
                'password' => __('auth.password'),
                'remember_me' => __('auth.remember_me'),
                'forgot_password' => __('auth.forgot_password'),
                'no_account' => __('auth.no_account'),
                'register' => __('auth.register'),
                'submit' => __('auth.submit'),
                'required' => __('validation.required'),
                'email_invalid' => __('validation.email'),
            ]
        ]);
    }

    public function store(Request $request, $locale)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($validated, $request->boolean('remember_me'))) {
            $request->session()->regenerate();

            return redirect()
                ->intended(route('dashboard.index', ['locale' => $locale]))
                ->with('success', __('auth.login_success'));
        }

        $request->session()->regenerate();

        return redirect()
            ->route('login', ['locale' => $locale])
            ->withErrors(['email' => __('auth.failed')]);
    }

    public function destroy($locale)
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()
            ->route('login', ['locale' => $locale])
            ->with('success', __('auth.logout_success'));
    }
}
