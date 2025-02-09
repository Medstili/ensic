<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get("/", [UserController::class, 'index'])->name('dashboard');
Route::resource('user', UserController::class);
Route::post('create-coach',[UserController::class, 'store'])->name('coach.store');
Route::get('/profile/{id}', [UserController::class, 'show'])->name('coach.show');
Route::get('/profile/{id}/edit', [UserController::class, 'edit'])->name('coach.edit');
Route::get('dashboard/create', [UserController::class, 'create'])->name('coach.create');
Route::put('/profile/{id}', [UserController::class, 'update'])->name('coach.update');
Route::delete('/profile/{id}', [UserController::class, 'destroy'])->name('coach.destroy');

