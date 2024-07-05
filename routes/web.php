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
            Route::get('/', 'CustomerController@index')->name('customer.index');
            Route::get('/data', 'CustomerController@data')->name('customer.data');
            Route::get('/create', 'CustomerController@form')->name('customer.create');
            Route::post('/store', 'CustomerController@store')->name('customer.store');
            Route::get('/edit/{id}', 'CustomerController@form')->name('customer.edit');
            Route::delete('/destroy/{id}', 'CustomerController@destroy')->name('customer.destroy');

            Route::get('/get-city/{province_id}', 'CustomerController@get_city')->name('customer.get_city');
            Route::get('/get-district/{city_id}', 'CustomerController@get_district')->name('customer.get_district');
        });
    });

});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

require __DIR__.'/auth.php';
