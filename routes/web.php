<?php

use App\Http\Controllers\LandlordProfileController;
use App\Http\Controllers\LandlordSetupController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::get('/landlord/setup', [LandlordSetupController::class, 'edit'])->name('landlord.setup.edit');
    // Route::post('/landlord/setup', [LandlordSetupController::class, 'update'])->name('landlord.setup.update');

    Route::get('/landlord/setup/{step?}', [LandlordSetupController::class, 'edit'])->where('step', '[1-3]')->name('landlord.setup.edit');
    Route::post('/landlord/setup/{step}', [LandlordSetupController::class, 'update'])->where('step', '[1-3]')->name('landlord.setup.update');
    Route::get('/landlord/setup/back/{step}', [LandlordSetupController::class, 'back'])->where('step', '[1-3]')->name('landlord.setup.back');

});

Route::middleware(['auth', 'verified', 'profile-completed'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/profile/landlord', [LandlordProfileController::class, 'update'])->name('landlord.update');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';
