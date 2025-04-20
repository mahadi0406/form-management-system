<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index']);
Route::get('/forms/json-config', function () {
    return Inertia::render('Forms/JsonConfig');
})->name('forms.json-config');
Route::resource('forms', \App\Http\Controllers\FormController::class);
Route::get('forms/{id}/preview', [\App\Http\Controllers\FormController::class, 'preview']);

