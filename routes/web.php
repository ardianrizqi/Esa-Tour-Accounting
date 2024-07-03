<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Auth::routes();

Route::middleware('auth')->group(function () {
    Route::namespace('App\Http\Controllers\Backend')->name('backend.')->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');

        Route::group(['prefix' => 'invoice'], function () {
            Route::get('/', 'InvoiceController@index')->name('invoice.index');
            Route::get('/create', 'InvoiceController@form')->name('invoice.create');
            Route::get('/get-city/{province_id}', 'InvoiceController@get_city')->name('invoice.get_city');
            Route::get('/get-district/{city_id}', 'InvoiceController@get_district')->name('invoice.get_district');
            Route::post('/customer-store', 'InvoiceController@customer_store')->name('invoice.customer_store');
        });

        Route::group(['prefix' => 'customer'], function () {

        });
    });

});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

require __DIR__.'/auth.php';
