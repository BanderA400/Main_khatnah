<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private const SUPPORTED_PROVIDERS = ['google', 'twitter'];

    public function redirect(string $provider)
    {
        abort_unless(in_array($provider, self::SUPPORTED_PROVIDERS, true), 404);

        return Socialite::driver($this->resolveDriver($provider))->redirect();
    }

    public function callback(Request $request, string $provider)
    {
        abort_unless(in_array($provider, self::SUPPORTED_PROVIDERS, true), 404);

        try {
            $socialUser = Socialite::driver($this->resolveDriver($provider))->user();
        } catch (\Throwable $exception) {
            report($exception);

            return $this->socialAuthFailed('تعذر إتمام تسجيل الدخول الاجتماعي. حاول مرة أخرى.');
        }

        $email = $socialUser->getEmail();

        if (! is_string($email) || blank($email)) {
            return $this->socialAuthFailed('تعذر جلب البريد الإلكتروني من المزود. استخدم Google أو سجل يدويًا.');
        }

        $account = SocialAccount::firstOrNew([
            'provider'    => $provider,
            'provider_id' => $socialUser->getId(),
        ]);

        $user = $account->user;

        if (!$account->user_id) {
            $name = $socialUser->getName();

            if (! is_string($name) || blank($name)) {
                $name = Str::of($email)->before('@')->headline()->toString();
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'     => $name,
                    'password' => Hash::make(Str::random(32)),
                ]
            );

            if ((bool) $user->is_admin) {
                return redirect('/app/login')
                    ->withErrors([
                        'social_auth' => 'حسابات الإدارة لا يمكنها تسجيل الدخول عبر الشبكات الاجتماعية. استخدم بوابة التحكم.',
                    ]);
            }

            $account->user_id = $user->id;
            $account->avatar  = $socialUser->getAvatar();
        }

        if ($user && (bool) $user->is_admin) {
            return redirect('/app/login')
                ->withErrors([
                    'social_auth' => 'حسابات الإدارة لا يمكنها تسجيل الدخول عبر الشبكات الاجتماعية. استخدم بوابة التحكم.',
                ]);
        }

        $account->provider_token = $socialUser->token ?? null;
        $account->save();

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended('/app');
    }

    private function socialAuthFailed(string $message)
    {
        return redirect('/app/login')->withErrors([
            'social_auth' => $message,
        ]);
    }

    private function resolveDriver(string $provider): string
    {
        return $provider === 'twitter' ? 'twitter-oauth-2' : $provider;
    }
}
