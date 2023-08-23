<?php

use App\Http\Controllers\Api\Auth\GithubController;
use App\Http\Controllers\Api\Auth\GoogleController;
use App\Http\Controllers\Api\Auth\YandexController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::get('auth/google', [GoogleController::class, 'signInWithGoogle']);
Route::get('callback/google', [GoogleController::class, 'callbackToGoogle']);

Route::get('callback/yandex', [YandexController::class, 'callbackToYandex']);

Route::get('callback/github', [GithubController::class, 'callbackToGithub']);
