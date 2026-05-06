<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_and_redirects_back(): void
    {
        $email = 'new.user@gmail.com';

        $response = $this->post('/register', [
            'email' => $email,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);

        $user = User::where('email', $email)->firstOrFail();
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_login_authenticates_and_redirects_home(): void
    {
        $user = User::factory()->create([
            'email' => 'member@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_logout_logs_user_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
