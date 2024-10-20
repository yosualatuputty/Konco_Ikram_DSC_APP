<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});

Route::post('/detect-video', [App\Http\Controllers\DetectController::class, 'detectVideo']);

Route::get('/main', [App\Http\Controllers\MainController::class, 'index'])->name('dashboard');
Route::get('/medical-operation', [App\Http\Controllers\MedicalController::class, 'index'])->name('medical.operation');
Route::get('/construction-operation', [App\Http\Controllers\ConstructionController::class, 'index'])->name('construction.operation');
Route::get('/manufacturing-operation', [App\Http\Controllers\ManufacturingController::class, 'index'])->name('manufacturing.operation');
Route::get('/statistics-apd', [App\Http\Controllers\Stat_APDController::class, 'index'])->name('stat-apd');
Route::get('/statistics-drowsy', [App\Http\Controllers\Stat_DrowsyController::class, 'index'])->name('stat-drowsy');
    // Additional worker routes (statistics)
Route::get('/worker', [App\Http\Controllers\Stat_APDController::class, 'worker'])->name('statistics-apd-worker');
// Route::get('/worker', [App\Http\Controllers\Stat_DrowsyController::class, 'worker'])->name('statistics-drowsy-worker');