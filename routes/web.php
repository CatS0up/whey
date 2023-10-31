<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', HomeController::class)->name('homepage');

Route::as('auth.')->group(base_path('routes/auth.php'));
Route::as('shared.')->group(base_path('routes/shared.php'));
Route::middleware('authenticate')->as('web.')->group(base_path('routes/web/web.php'));
