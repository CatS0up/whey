<?php

declare(strict_types=1);

use App\Http\Controllers\Web\Media\UploadCKEditorImageController;
use Illuminate\Support\Facades\Route;

Route::post('ckeditor-image/upload', UploadCKEditorImageController::class)
    ->middleware('can:upload-ckeditor-images')
    ->name('ckeditorImage.upload');
