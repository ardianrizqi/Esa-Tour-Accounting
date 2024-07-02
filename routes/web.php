<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Auth::routes();

Route::middleware('auth')->group(function () {
    Route::namespace('App\Http\Controllers\Backend')->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('backend.dashboard.index');
    });

});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

require __DIR__.'/auth.php';
