<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;

Route::prefix('admin')->name('admin.')->group(function() {
    Auth::routes([
        'register'  => false,
        'verify'    => false
    ]);
    Route::middleware('auth:admin')->group(function() {
        Route::get('/', 'AdminController@index')->name('dashboard.index');
        Route::resource('cars', 'CarController');
        Route::post('cars/{car}/toggle', 'CarController@toggleActivation')->name('cars.toggle');
    });
});
