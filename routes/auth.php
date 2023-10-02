<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\EmailVerifyController;
use App\Http\Controllers\Auth\EmailVerifyResendController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetTemporaryPasswordController;
use App\Http\Controllers\Auth\SendTemporaryPasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')
    ->group(function (): void {
        /** REGISTRATION - START */
        Route::view('/register', 'auth.sections.register')->name('register.show');
        /** REGISTRATION - END */

        /** LOGIN - START */
        Route::prefix('login')
            ->as('login.')
            ->controller(LoginController::class)
            ->group(function (): void {
                Route::get('/', 'show')->name('show');
                Route::post('/', 'login')->name('request');
        });
        /** LOGIN - END */

        /** LOGOUT - START */
        Route::post('logout', LogoutController::class)->name('logout');
        /** LOGOUT - END */

        /** EMAIL VERIFICATION - START */
        Route::prefix('email-verification/{token:token}')
            ->as('emailVerification.')
            ->group(function (): void {
                /** VERIFY - START */
                Route::post('/', EmailVerifyController::class)->name('verify');
                /** VERIFY - END */

                /** RESEND - START */
                Route::prefix('resend')
                    ->as('resend.')
                    ->controller(EmailVerifyResendController::class)
                    ->group(function (): void {
                        Route::get('/', 'show')->name('show');
                        Route::post('/', 'resend')->name('request');
                });
                /** RESEND - END */
        });
        /** EMAIL VERIFICATION - END */
    });

/** TEMPORARY PASSWORD - START */
Route::prefix('temporary-password')
    ->as('temporaryPassword.')
    ->group(function (): void {
        /** RESET - START */
        Route::middleware('authenticate')
            ->withoutMiddleware('reset_password')
            ->prefix('reset')
            ->as('reset.')
            ->controller(ResetTemporaryPasswordController::class)
            ->group(function (): void {
                Route::get('/', 'show')->name('show');
                Route::post('/', 'reset')->name('request');
        });
        /** RESET - END */

        /** SEND - START */
        Route::middleware('guest')
            ->prefix('send')
            ->as('send.')
            ->controller(SendTemporaryPasswordController::class)
            ->group(function (): void {
                Route::get('/', 'show')->name('show');
                Route::get('/', 'send')->name('request');
        });
        /** SEND - END */
});
/** TEMPORARY PASSWORD - END */
