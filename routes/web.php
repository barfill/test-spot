<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Models\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/'.config('app.locale'));
});

Route::prefix('{locale}')
    ->where(['locale' => implode('|', config('app.supported_locales'))])
    ->group(function() {
        Route::middleware('auth')->group(function() {
            Route::get('/', function() {
                return redirect()->route('dashboard.index', ['locale' => app()->getLocale()]);
            });
            Route::resource('dashboard', DashboardController::class);
            Route::delete('logout', [AuthenticationController::class, 'destroy'])->name('logout');
        });

        Route::get('login', [AuthenticationController::class, 'create'])->name('login');
        Route::post('login', [AuthenticationController::class, 'store'])->name('login.store');

        Route::get('register', [RegistrationController::class, 'create'])->name('register');
        Route::post('register', [RegistrationController::class, 'store'])->name('register.store');
    });
