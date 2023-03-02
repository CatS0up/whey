<?php

declare(strict_types=1);

use App\Http\Controllers\Shared\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('locale/{locale}', LocaleController::class)
    ->name('switchLocale')
    ->where('locale', '[a-z]{2}');
