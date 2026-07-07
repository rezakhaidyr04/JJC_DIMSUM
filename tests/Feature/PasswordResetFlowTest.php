<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_password_reset_using_email(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'fitri@example.com',
        ]);

        $response = $this->post(route('password.email'), [
            'identifier' => 'fitri@example.com',
        ]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_user_can_request_password_reset_using_whatsapp_identifier(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'andi@example.com',
            'whatsapp' => '+6281234567890',
        ]);

        $response = $this->post(route('password.email'), [
            'identifier' => '081234567890',
        ]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'sari@example.com',
            'password' => Hash::make('password'),
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}