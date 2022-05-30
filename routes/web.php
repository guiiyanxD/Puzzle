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

Route::resource('/file', \App\Http\Controllers\FileController::class);

Route::get('/load_image', function (){
   return view('board');
})->name('loadImage');

Route::get('/images',[\App\Http\Controllers\FileController::class,'indexImages'])->name('indexImages');

Route::post('/store_image', [\App\Http\Controllers\FileController::class, 'storeImage'])->name('storeImage');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
