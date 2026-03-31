<?php

use App\Http\Controllers\AyahReadController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [AyahReadController::class, 'index'])->name('dashboard');
    Route::get('history', [AyahReadController::class, 'history'])->name('history');
    Route::post('ayah', [AyahReadController::class, 'store'])->name('ayah.store');
});

require __DIR__.'/settings.php';
