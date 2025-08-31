<?php

use App\Http\Controllers\DashboardController;
use App\Models\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/'.config('app.locale'));
});

Route::prefix('{locale}')
    ->where(['locale' => implode('|', config('app.supported_locales'))])
    ->group(function() {
        Route::get('/', function() {
            return redirect()->route('dashboard.index', ['locale' => app()->getLocale()]);
        });
        Route::resource('dashboard', DashboardController::class);
    });


