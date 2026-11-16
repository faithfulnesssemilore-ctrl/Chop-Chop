<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::post('/reservations', [ReservationController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('reservations.store');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');
Route::post('/newsletter', [NewsletterController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('newsletter.store');
