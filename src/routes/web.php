<?php

use Illuminate\Support\Facades\Route;
use amdadulshakib\installer\Controllers\InstallController;

Route::middleware(['web', 'install.lock'])->prefix('install')->group(function () {
    Route::get('/', [InstallController::class, 'welcome'])->name('installer.welcome');
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('installer.requirements');
    Route::get('/environment', [InstallController::class, 'environmentForm'])->name('installer.environment');
    Route::post('/environment/save', [InstallController::class, 'saveEnvironment'])->name('installer.environment.save');
    Route::get('/database', [InstallController::class, 'databaseForm'])->name('installer.database');
    Route::post('/database/save', [InstallController::class, 'saveDatabase'])->name('installer.database.save');
    Route::get('/admin', [InstallController::class, 'adminForm'])->name('installer.admin');
    Route::post('/admin/save', [InstallController::class, 'saveAdmin'])->name('installer.admin.save');
    Route::get('/finish', [InstallController::class, 'finish'])->name('installer.finish')->withoutMiddleware('install.lock');
});
