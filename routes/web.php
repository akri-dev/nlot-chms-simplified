<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarriageController;
use App\Http\Controllers\ProfileController;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // PROFILES

    # General Profile List
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles');
    Route::get('/profiles/create', [ProfileController::class, 'create'])->name('profiles.create');
    Route::post('/profiles/save', [ProfileController::class, 'save'])->name('profiles.save');

    # Profile Anniversaries
    Route::get('/profiles/anniversaries', [ProfileController::class, 'anniversaries'])->name('profiles.anniversaries');
    Route::post('/profiles/anniversaries/store', [MarriageController::class, 'store'])->name('profiles.anniversaries.store');

    # Profile Edit
    Route::get('/profiles/{profile}', [ProfileController::class, 'profile'])->name('profiles.profile');
    Route::patch('/profiles/{profile}/update', [ProfileController::class, 'update'])->name('profiles.profile.update');

});

