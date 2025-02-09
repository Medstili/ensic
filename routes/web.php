<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get("dashboard", [UserController::class, 'index'])->name('dashboard');
Route::resource('user', UserController::class);
Route::view('/', 'login_form')->name('login');

