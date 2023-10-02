<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\EmailVerifyController;
use App\Http\Controllers\Auth\EmailVerifyResendController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SendTemporaryPasswordController;
use Illuminate\Support\Facades\Route;

// Guest routes - start
Route::middleware('guest')
    ->group(
        function (): void {
            Route::view('/register', 'auth.sections.register')
                ->name('register.show');

            Route::controller(LoginController::class)
                ->as('login.')
                ->group(
                    function (): void {
                        Route::get('/login', 'show')
                            ->name('show');

                        Route::post('/login', 'login')
                            ->name('login');
                    },
                );

            Route::prefix('email-verification')
                ->as('emailVerify.')
                ->group(function (): void {
                    Route::post('{token:token}', EmailVerifyController::class)
                        ->name('verify');

                    Route::controller(EmailVerifyResendController::class)
                        ->prefix('resend')
                        ->as('resend.')
                        ->group(
                            function (): void {
                                Route::get('{token:token}', 'show')
                                    ->name('show');

                                Route::post('{token:token}', 'resend')
                                    ->name('resend');
                            },
                        );
                }, );

            Route::prefix('temporary-password')
                ->as('temporaryPassword.')
                ->group(function (): void {
                    Route::controller(SendTemporaryPasswordController::class)
                        ->group(
                            function (): void {
                                Route::get('/', 'show')
                                    ->name('show');

                                Route::post('/', 'send')
                                    ->name('send');
                            },
                        );
                });
        },
    );
// Guest routes - end

// Auth routes - start
Route::middleware('authenticate')
    ->group(function (): void {
        Route::post('/logout', LogoutController::class)
            ->name('logout');

        Route::controller(ResetPasswordController::class)
            ->prefix('reset-password')
            ->as('resetPassword.')
            ->withoutMiddleware('reset_password')
            ->group(function (): void {
                Route::get('/', 'show')
                    ->name('show');

                Route::post('/', 'reset')
                    ->name('reset');
            });
    });
// Auth routes - end
