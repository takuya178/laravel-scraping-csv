<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('products/{title}-{id}', 'ProductController@showWelcomePage')->
    name('products.show');

Route::get('categories/{title}-{id}/products', 'CategoryProductController@showProducts')->name('categories.products.show');

Route::get('authorization', 'Auth\LoginController@authorization')->name('authorization');
