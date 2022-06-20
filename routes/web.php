<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
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
Route::resource('/status',\App\Http\Controllers\StatusGameController::class)->only('index', 'create','store');

//New Game window
Route::get('/game/difficulty',[\App\Http\Controllers\GameController::class, 'create'])->name('createGame');
Route::post('/game/difficulty/set',[\App\Http\Controllers\GameController::class, 'store'])->name('storeGame');
Route::get('/game/start/{id}',[\App\Http\Controllers\GameController::class, 'start'])->name('startGame');
Route::post('/game/join',[\App\Http\Controllers\GameController::class,'joinToGame'])->name('joinGame');
Route::put('/game/setWinner',[\App\Http\Controllers\GameController::class,'setWinner'])->name('setWinner');


//GameSession Updates
Route::put('/game/addScore',[\App\Http\Controllers\GameSessionController::class,'addScore'])->name('addScore');
Route::put('/game/addMovement',[\App\Http\Controllers\GameSessionController::class,'addMovement'])->name('addMovement');
Route::put('/game/subScore',[\App\Http\Controllers\GameSessionController::class,'subScore'])->name('subScore');


//See saved games
Route::get('games/saved/{id}',[\App\Http\Controllers\GameController::class,'show'])->name('savedGames');



Route::get('/load_image', function (){
   return view('board');
})->name('loadImage');

Route::get('/images',[\App\Http\Controllers\FileController::class,'indexImages'])->name('indexImages');

Route::post('/store_image', [\App\Http\Controllers\FileController::class, 'storeImage'])->name('storeImage');

Auth::routes();
//Broadcast::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
