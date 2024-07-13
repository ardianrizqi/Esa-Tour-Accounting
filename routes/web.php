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
            Route::get('/data', 'InvoiceController@data')->name('invoice.data');
            Route::get('/create', 'InvoiceController@form')->name('invoice.create');
            Route::get('/edit/{id}', 'InvoiceController@form')->name('invoice.edit');
            Route::post('/store', 'InvoiceController@store')->name('invoice.store');
            Route::get('/show/{id}', 'InvoiceController@show')->name('invoice.show');
            Route::post('/update-detail/{id}', 'InvoiceController@update_details')->name('invoice.update_details');
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

        Route::group(['prefix' => 'product'], function () {
            Route::get('/', 'ProductController@index')->name('product.index');
            Route::get('/data', 'ProductController@data')->name('product.data');
            Route::get('/create', 'ProductController@form')->name('product.create');
            Route::post('/store', 'ProductController@store')->name('product.store');
            Route::get('/edit/{id}', 'ProductController@form')->name('product.edit');
            Route::delete('/destroy/{id}', 'ProductController@destroy')->name('product.destroy');
        });

        Route::group(['prefix' => 'bank'], function () {
            Route::get('/', 'BankController@index')->name('bank.index');
            Route::get('/data', 'BankController@data')->name('bank.data');
            Route::get('/create', 'BankController@form')->name('bank.create');
            Route::post('/store', 'BankController@store')->name('bank.store');
            Route::get('/edit/{id}', 'BankController@form')->name('bank.edit');
            Route::delete('/destroy/{id}', 'BankController@destroy')->name('bank.destroy');
        });

        Route::group(['prefix' => 'physical-invoice'], function () {
            Route::get('/', 'PhysicalInvoiceController@index')->name('physical_invoice.index');
            Route::get('/data', 'PhysicalInvoiceController@data')->name('physical_invoice.data');
            Route::get('/create', 'PhysicalInvoiceController@form')->name('physical_invoice.create');
            Route::post('/store', 'PhysicalInvoiceController@store')->name('physical_invoice.store');
            Route::get('/edit/{id}', 'PhysicalInvoiceController@form')->name('physical_invoice.edit');
            Route::delete('/destroy/{id}', 'PhysicalInvoiceController@destroy')->name('physical_invoice.destroy');
        });
    });

});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

require __DIR__.'/auth.php';
