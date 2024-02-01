<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
    return redirect()->route('users.index');
});

Route::get('/audio', function () {
    return view('audio');
});

Route::resource('users', UserController::class)->except(['show']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::get('/users/search', [UserController::class, 'searchUsers'])->name('users.search');
Route::get('/users/export', [UserController::class, 'exportUsers'])->name('users.export');
Route::get('/audio-length', [UserController::class, 'getAudioLength']);
Route::get('/calculate-distance', [UserController::class, 'showDistance']);