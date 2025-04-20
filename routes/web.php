<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index']);
Route::resource('forms', \App\Http\Controllers\FormController::class);
Route::get('forms/{id}/preview', [\App\Http\Controllers\FormController::class, 'preview']);
