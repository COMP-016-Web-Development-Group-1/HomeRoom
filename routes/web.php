<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandlordProfileController;
use App\Http\Controllers\LandlordSetupController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TransactionController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/landlord', [LandlordProfileController::class, 'update'])->name('landlord.update');

    Route::get('/properties', [PropertyController::class, 'index'])->name('property.index');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transaction.index');

    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcement.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcement.store');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcement.create');
    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcement.show');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcement.update');
    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcement.edit');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcement.destroy');

    Route::get('/requests', [MaintenanceRequestController::class, 'index'])->name('request.index');

    Route::view('/tests', 'table');
});

require __DIR__ . '/auth.php';
