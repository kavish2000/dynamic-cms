<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->name('pages.index');
Route::resource('pages', PageController::class);

// Catch-all route for nested pages
Route::get('{path}', [PageController::class, 'show'])
    ->where('path', '.*')
    ->name('pages.show');
