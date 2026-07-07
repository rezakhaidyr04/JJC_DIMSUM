<?php

namespace Tests\Feature;

use App\Mail\NewAccountOwnerMail;
use App\Mail\RegistrationOtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationOtpFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_sends_otp_and_owner_notification_without_logging_in(): void
    {
        Mail::fake();

        $owner = User::factory()->owner()->create([
            'email' => 'owner@example.com',
        ]);

        $response = $this->post(route('register'), [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('registration.verify.form', ['email' => 'budi@example.com']));
        $this->assertGuest();

        $user = User::where('email', 'budi@example.com')->firstOrFail();
        $this->assertTrue($user->hasPendingRegistrationOtp());

        Mail::assertSent(RegistrationOtpMail::class, function (RegistrationOtpMail $mail) {
            return $mail->hasTo('budi@example.com');
        });

        Mail::assertSent(NewAccountOwnerMail::class, function (NewAccountOwnerMail $mail) use ($owner) {
            return $mail->hasTo($owner->email);
        });
    }

    public function test_pending_registration_cannot_login_until_otp_is_verified(): void
    {
        $user = User::factory()->create([
            'email' => 'sari@example.com',
            'password' => Hash::make('password'),
        ]);

        $user->storeRegistrationOtp('123456');

        $response = $this->post(route('login'), [
            'identifier' => 'sari@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('identifier');
        $this->assertGuest();
    }

    public function test_user_can_verify_otp_and_login_afterwards(): void
    {
        $user = User::factory()->create([
            'email' => 'andi@example.com',
            'password' => Hash::make('password'),
        ]);

        $user->storeRegistrationOtp('654321');

        $response = $this->post(route('registration.verify'), [
            'email' => 'andi@example.com',
            'otp' => '654321',
        ]);

        $response->assertRedirect(route('login'));

        $user->refresh();
        $this->assertFalse($user->hasPendingRegistrationOtp());
        $this->assertNotNull($user->email_verified_at);

        $loginResponse = $this->post(route('login'), [
            'identifier' => 'andi@example.com',
            'password' => 'password',
        ]);

        $loginResponse->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}