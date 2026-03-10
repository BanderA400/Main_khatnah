<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ControlRecoveryController extends Controller
{
    public function show(): View
    {
        $config = $this->getRecoveryConfig();
        $this->ensureRecoveryIsEnabled($config);

        return view('auth.control-recovery', [
            'recoveryEmail' => (string) $config['email'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $config = $this->getRecoveryConfig();
        $this->ensureRecoveryIsEnabled($config);

        $validated = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = mb_strtolower(trim((string) $validated['email']));
        $expectedEmail = mb_strtolower(trim((string) $config['email']));
        $expectedToken = (string) $config['token'];
        $providedToken = (string) $validated['token'];

        if (
            $email === '' ||
            $expectedEmail === '' ||
            ! hash_equals($expectedEmail, $email) ||
            ! hash_equals($expectedToken, $providedToken)
        ) {
            throw ValidationException::withMessages([
                'email' => 'بيانات الاستعادة غير صحيحة.',
            ]);
        }

        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [$expectedEmail])
            ->first();

        if (! $user && ! (bool) ($config['create_if_missing'] ?? true)) {
            throw ValidationException::withMessages([
                'email' => 'لا يوجد حساب مطابق للبريد المحدد.',
            ]);
        }

        if (! $user) {
            $user = User::query()->create([
                'name' => (string) ($config['name'] ?? 'System Admin'),
                'email' => $expectedEmail,
                'password' => (string) $validated['password'],
                'is_admin' => true,
                'email_verified_at' => now(),
            ]);
        } else {
            $user->forceFill([
                'password' => Hash::make((string) $validated['password']),
                'is_admin' => true,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect('/control/dashboard');
    }

    private function getRecoveryConfig(): array
    {
        return (array) config('security.control_recovery', []);
    }

    private function ensureRecoveryIsEnabled(array $config): void
    {
        $enabled = (bool) ($config['enabled'] ?? false);
        $hasEmail = filled((string) ($config['email'] ?? ''));
        $hasToken = filled((string) ($config['token'] ?? ''));

        abort_unless($enabled && $hasEmail && $hasToken, 404);
    }
}
