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

Route::get('/', function() {
    return redirect('products');
});

Route::resource('orders', \App\Http\Controllers\OrderController::class, ['names' => 'orders']);
Route::resource('products', \App\Http\Controllers\ProductController::class, ['names' => 'products']);

Route::post('orders/generate-snap-token', [App\Http\Controllers\OrderController::class, 'generateSnapToken'])->name('orders.generateSnapToken');
Route::post('orders/save-order', [App\Http\Controllers\OrderController::class, 'saveOrder'])->name('orders.saveOrder');