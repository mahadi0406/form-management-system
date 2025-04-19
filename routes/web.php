<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('forms.index');
});

Route::resource('forms', \App\Http\Controllers\FormController::class);
