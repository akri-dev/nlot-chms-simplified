<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles');
    Route::get('/profiles/create', [ProfileController::class, 'create'])->name('profiles.create');
});

