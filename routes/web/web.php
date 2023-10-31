<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::as('dashboard.')->group(base_path('routes/web/dashboard.php'));
Route::prefix('media')->as('media.')->group(base_path('routes/web/media.php'));
