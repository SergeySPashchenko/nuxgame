<?php

use App\Http\Controllers\AccessLinkController;
use App\Http\Controllers\RegistrationController;
use App\Http\Middleware\EnsureValidAccessLink;
use Illuminate\Support\Facades\Route;

// Registration (no auth)
Route::get('/', [RegistrationController::class, 'create'])->name('register');
Route::get('/register', [RegistrationController::class, 'create']);
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

// Page A — every action requires a valid access link (active + not expired)
Route::middleware([EnsureValidAccessLink::class])->group(function (): void {
    Route::get('/page/{accessLink:token}', [AccessLinkController::class, 'show'])->name('page.show');
    Route::post('/page/{accessLink:token}/regenerate', [AccessLinkController::class, 'regenerate'])->name('page.regenerate');
    Route::post('/page/{accessLink:token}/deactivate', [AccessLinkController::class, 'deactivate'])->name('page.deactivate');
    Route::post('/page/{accessLink:token}/lucky', [AccessLinkController::class, 'lucky'])->name('page.lucky');
    Route::get('/page/{accessLink:token}/history', [AccessLinkController::class, 'history'])->name('page.history');
});
