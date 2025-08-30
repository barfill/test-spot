<?php

use App\Http\Controllers\DashboardController;
use App\Models\Dashboard;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return inertia('Dashboard/Index');
// });


Route::prefix('{locale}')
    ->where(['locale' => implode('|', array_keys(config('app.supported_locales')))])
    ->group(function() {
        Route::get('/', [DashboardController::class, 'index']);
        Route::resource('dashboard', DashboardController::class);
    });


