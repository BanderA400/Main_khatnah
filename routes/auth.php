<?php

use App\Models\User;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use Filament\Auth\Notifications\VerifyEmail;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', fn () => redirect()->route('filament.admin.auth.register'))
        ->name('register');

    Route::get('login', fn () => redirect()->route('filament.admin.auth.login'))
        ->name('login');

    Route::get('forgot-password', fn () => redirect()->route('filament.admin.auth.password-reset.request'))
        ->name('password.request');

    Route::get('reset-password/{token}', function (Request $request, string $token) {
        $email = $request->query('email');

        if (filled($email)) {
            $user = User::where('email', $email)->first();

            if ($user) {
                return redirect(Filament::getPanel('admin')->getResetPasswordUrl($token, $user));
            }
        }

        return redirect()->route('filament.admin.auth.password-reset.request');
    })->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', fn () => redirect()->route('filament.admin.auth.email-verification.prompt'))
        ->name('verification.notice');

    Route::post('email/verification-notification', function (Request $request) {
        $user = $request->user();

        if (! $user instanceof MustVerifyEmail || $user->hasVerifiedEmail()) {
            return redirect()->intended(Filament::getUrl());
        }

        $notification = app(VerifyEmail::class);
        $notification->url = Filament::getPanel('admin')->getVerifyEmailUrl($user);

        $user->notify($notification);

        return back()->with('status', 'verification-link-sent');
    })
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', function (Request $request) {
        auth()->guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});
