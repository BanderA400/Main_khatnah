<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertRedirect(route('filament.admin.auth.register'));
    }

    public function test_filament_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('filament.admin.auth.register'));

        $response->assertStatus(200);
    }

    public function test_legacy_registration_post_route_is_not_available(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $this->assertSame(0, User::count());
        $response->assertStatus(405);
    }
}
