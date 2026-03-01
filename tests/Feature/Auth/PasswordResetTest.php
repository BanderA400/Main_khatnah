<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertRedirect(route('filament.admin.auth.password-reset.request'));
    }

    public function test_filament_reset_password_request_screen_can_be_rendered(): void
    {
        $response = $this->get(route('filament.admin.auth.password-reset.request'));

        $response->assertStatus(200);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->get(Filament::getPanel('admin')->getResetPasswordUrl($token, $user));

        $response->assertStatus(200);
    }

    public function test_legacy_reset_password_route_redirects_to_filament_reset_password_screen(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->get('/reset-password/'.$token.'?email='.$user->email);

        $response->assertRedirectContains('/app/password-reset/reset');
        $response->assertRedirectContains('token='.$token);
        $response->assertRedirectContains('email='.urlencode($user->email));
    }
}
