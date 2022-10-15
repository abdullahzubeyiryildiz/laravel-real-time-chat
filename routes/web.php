<?php

use Illuminate\Support\Facades\Auth;
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


Auth::routes();

Route::get('/', [App\Http\Controllers\ChatController::class, 'index'])->name('home');

Route::get('/cikis', [App\Http\Controllers\ChatController::class, 'logout'])->name('logout');



Route::get('/users/list', [App\Http\Controllers\ChatController::class, 'getUsers']);


Route::get('/chat/{uuid}', [App\Http\Controllers\ChatController::class, 'detail'])->name('chat.detail');


Route::post('/message/send', [App\Http\Controllers\ChatController::class, 'sendMessage'])->name('message.insert');


Route::post('/get/messageList', [App\Http\Controllers\ChatController::class, 'messageList'])->name('message.list');



Route::post('/user/socket/login', [App\Http\Controllers\ChatController::class, 'loginsocket'])->name('user.socket');
