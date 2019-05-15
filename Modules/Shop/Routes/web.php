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

Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', 'ShopController@index')->name('index');
    Route::get('/cart', 'CartController@index')->name('cart.index');
    Route::post('cart/add/{car}', 'CartController@add')->name('cart.add');
    Route::post('cart/remove/{cartItem}', 'CartController@remove')
        ->name('cart.remove');
    Route::post('cart/update', 'CartController@update')
        ->name('cart.update');

    Route::get('cart/destroy', 'CartController@destroy')
        ->name('cart.destroy')->middleware('auth');
});
