<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentUserController;
use App\Models\Dashboard;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/'.config('app.locale'));
});

Route::prefix('{locale}')
    ->where(['locale' => implode('|', config('app.supported_locales'))])
    ->group(function() {
        Route::post('dashboard/{dashboard}/assignments/{assignment}/compile', [AssignmentController::class, 'compileSubmissions'])
                ->name('dashboard.assignment.compile'); // Bez autentykacji pod postmana
        Route::middleware('auth')->group(function() {
            Route::get('/', function() {
                return redirect()->route('dashboard.index', ['locale' => app()->getLocale()]);
            });
            Route::resource('dashboard', DashboardController::class);
            Route::resource('dashboard.users', DashboardUserController::class)
                ->only(['index', 'update']);
            Route::resource('dashboard.assignments', AssignmentController::class)
                ->except(['index']);
            Route::resource('dashboard.assignment.submissions', AssignmentUserController::class)
                ->parameters(['submissions' => 'assignmentUser']);

            Route::post('dashboard/{dashboard}/assignment/{assignment}/submissions/{assignmentUser}/run-check',
                [AssignmentUserController::class, 'runCheck']
            )->name('assignment.submissions.run-check');
            // Route::post('dashboard/{dashboard}/assignments/{assignment}/compile', [AssignmentController::class, 'compileSubmissions'])
            //     ->name('dashboard.assignment.compile');
            Route::post('dashboard/{dashboard}/assignments/{assignment}/compile/{assignmentUser}', [AssignmentUserController::class, 'compileSubmission'])
                ->name('dashboard.assignment.submission.compile');
            Route::post('dashboard/{dashboard}/assignment/{assignment}/submit', [AssignmentController::class, 'submit'])
                ->name('assignment.submit');

            Route::delete('logout', [AuthenticationController::class, 'destroy'])->name('logout');
        });

        Route::get('login', [AuthenticationController::class, 'create'])->name('login');
        Route::post('login', [AuthenticationController::class, 'store'])->name('login.store');

        Route::get('register', [RegistrationController::class, 'create'])->name('register');
        Route::post('register', [RegistrationController::class, 'store'])->name('register.store');
    });
