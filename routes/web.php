<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::resource('user', UserController::class);
Route::resource('appointment', AppointmentController::class);
Route::resource('patient',PatientController::class);
Route::view('/booking', 'booking_appointment')->name('booking');
Route::view('/login', 'login_form')->name('login');
// Route::get('/coaches/by-specialty/{specialty}', [UserController::class, 'getCoachesBySpecialty'])->name('coaches.by-specialty');
Route::view('/', 'global_dashboard')->name('global');
// Route::post('/check-availability', [AppointmentController::class, 'checkAvailability'])->name('check-availability');
Route::post('/appointments/{id}/upload-report', [AppointmentController::class, 'uploadReport'])->name('appointments.uploadReport');
Route::get('/appointments/{id}/download-report', [AppointmentController::class, 'downloadReport'])->name('appointments.downloadReport');
Route::delete('/appointments/{id}/delete-report', [AppointmentController::class, 'deleteReport'])->name('appointments.deleteReport');
Route::get('/appointments/{id}/view-report', [AppointmentController::class, 'viewReport'])->name('appointments.viewReport');
Route::patch( '/appointments/{id}/{status}/update-appointment-status', [AppointmentController::class, 'updateAppointmentStatus'])->name('appointments.update-appointment-status');
Route::post('/coach-availability', [AppointmentController::class, 'findAvailableCoach'])
    ->name('coach-availability');
Route::view('/appointment-Cancellation','appointment_cancellation')->name('appointment-cancellation');





