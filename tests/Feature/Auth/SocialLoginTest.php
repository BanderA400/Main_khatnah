<?php

namespace Tests\Feature\Auth;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Provider as SocialiteProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class SocialLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_unknown_provider_returns_not_found(): void
    {
        $this->get('/auth/facebook')->assertNotFound();
    }

    public function test_filament_login_displays_social_buttons(): void
    {
        $response = $this->get(route('filament.admin.auth.login'));

        $response->assertOk();
        $response->assertSee('الدخول عبر Google');
        $response->assertSee('الدخول عبر X');
    }

    public function test_filament_register_displays_social_buttons(): void
    {
        $response = $this->get(route('filament.admin.auth.register'));

        $response->assertOk();
        $response->assertSee('التسجيل عبر Google');
        $response->assertSee('التسجيل عبر X');
    }

    public function test_callback_rejects_when_provider_has_no_email(): void
    {
        $provider = $this->mock(SocialiteProvider::class);
        $provider->shouldReceive('user')->once()->andReturn(
            $this->makeSocialUser(
                id: 'tw-no-email',
                name: 'Twitter User',
                email: null,
                avatar: 'https://example.com/avatar.png',
                token: 'token-1',
            )
        );

        Socialite::shouldReceive('driver')->once()->with('twitter-oauth-2')->andReturn($provider);

        $response = $this->get('/auth/twitter/callback');

        $response->assertRedirect('/app/login');
        $response->assertSessionHasErrors('social_auth');
        $this->assertGuest();
        $this->assertDatabaseCount('social_accounts', 0);
    }

    public function test_callback_blocks_admin_account(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        $provider = $this->mock(SocialiteProvider::class);
        $provider->shouldReceive('user')->once()->andReturn(
            $this->makeSocialUser(
                id: 'google-admin',
                name: 'Admin Social',
                email: $admin->email,
                avatar: 'https://example.com/admin.png',
                token: 'token-admin',
            )
        );

        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/app/login');
        $response->assertSessionHasErrors('social_auth');
        $this->assertGuest();
        $this->assertDatabaseMissing('social_accounts', [
            'provider' => 'google',
            'provider_id' => 'google-admin',
        ]);
    }

    public function test_callback_creates_user_and_social_account_and_logs_in(): void
    {
        $provider = $this->mock(SocialiteProvider::class);
        $provider->shouldReceive('user')->once()->andReturn(
            $this->makeSocialUser(
                id: 'google-100',
                name: 'Social Member',
                email: 'member@example.com',
                avatar: 'https://example.com/member.png',
                token: 'token-100',
            )
        );

        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/app');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'member@example.com',
        ]);
        $this->assertDatabaseHas('social_accounts', [
            'provider' => 'google',
            'provider_id' => 'google-100',
        ]);

        $account = SocialAccount::query()->firstOrFail();

        $this->assertSame('token-100', $account->provider_token);
        $this->assertNotSame('token-100', $account->getRawOriginal('provider_token'));
    }

    public function test_callback_handles_provider_exception_gracefully(): void
    {
        $provider = $this->mock(SocialiteProvider::class);
        $provider->shouldReceive('user')->once()->andThrow(new \RuntimeException('OAuth failed'));

        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/app/login');
        $response->assertSessionHasErrors('social_auth');
        $this->assertGuest();
    }

    private function makeSocialUser(
        string $id,
        ?string $name,
        ?string $email,
        ?string $avatar,
        ?string $token,
    ): SocialiteUser {
        $user = new SocialiteUser();
        $user->map([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'avatar' => $avatar,
        ]);
        $user->token = $token;

        return $user;
    }
}
