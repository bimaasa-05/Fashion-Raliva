<?php

namespace Tests\Feature;

use App\Mail\ResetPasswordOtpMail;
use App\Models\PasswordResetOtp;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        app()->setLocale('id');
    }

    private function makeUser(string $password = 'OldPassword1'): User
    {
        $role = Role::firstOrCreate(
            ['nama_role' => Role::CUSTOMER],
            ['deskripsi' => Role::CUSTOMER, 'status' => 'aktif']
        );

        return User::create([
            'nama_lengkap' => 'Test Reset',
            'email' => 'reset-'.uniqid().'@raliva.test',
            'password' => Hash::make($password),
            'role_id' => $role->role_id,
            'status' => User::STATUS_AKTIF,
            'email_verified_at' => now(),
        ]);
    }

    private function makeOtp(User $user, string $otp = '123456', array $overrides = []): PasswordResetOtp
    {
        return PasswordResetOtp::create(array_merge([
            'email' => $user->email,
            'otp_hash' => Hash::make($otp),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(10),
            'last_sent_at' => now(),
        ], $overrides));
    }

    private function verifiedSession(User $user): array
    {
        return [
            'password_otp_email' => $user->email,
            'password_otp_verified' => [
                'email' => $user->email,
                'expires_at' => now()->addMinutes(10)->timestamp,
            ],
        ];
    }

    public function test_forgot_password_page_renders(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee(route('password.email'), false)
            ->assertSee(__('KIRIM KODE OTP'));
    }

    public function test_otp_form_renders_when_email_is_in_session(): void
    {
        $this->withSession(['password_otp_email' => 'someone@raliva.test'])
            ->get(route('password.otp.form'))
            ->assertOk()
            ->assertSee(route('password.otp.verify'), false)
            ->assertDontSee('name="password"', false)
            ->assertSee('someone@raliva.test')
            ->assertSee(__('Verifikasi Kode OTP'));
    }

    public function test_otp_form_redirects_to_request_page_without_email(): void
    {
        $this->get(route('password.otp.form'))
            ->assertRedirect(route('password.request'));
    }

    public function test_otp_request_sends_mail_and_stores_hashed_code(): void
    {
        Mail::fake();

        $user = $this->makeUser();

        $response = $this->post(route('password.email'), ['email' => $user->email]);

        $response->assertRedirect(route('password.otp.form'));
        $response->assertSessionHas('password_otp_email', $user->email);

        $otp = null;

        Mail::assertSent(ResetPasswordOtpMail::class, function (ResetPasswordOtpMail $mail) use ($user, &$otp) {
            $otp = $mail->otp;

            return $mail->hasTo($user->email);
        });

        $this->assertIsString($otp);
        $this->assertSame(6, strlen($otp));

        $record = PasswordResetOtp::where('email', $user->email)->firstOrFail();

        $this->assertSame(0, $record->attempts);
        $this->assertTrue(Hash::check($otp, $record->otp_hash));
        $this->assertNotSame($otp, $record->otp_hash);
        $this->assertTrue($record->expires_at->isFuture());
    }

    public function test_otp_request_for_unknown_email_uses_generic_flow_without_sending_mail(): void
    {
        Mail::fake();

        $email = 'unknown-'.uniqid().'@raliva.test';

        $response = $this->post(route('password.email'), ['email' => $email]);

        $response->assertRedirect(route('password.otp.form'));
        $response->assertSessionHas('password_otp_email', $email);

        Mail::assertNothingSent();
        $this->assertDatabaseMissing('password_reset_otps', ['email' => $email]);
    }

    public function test_correct_otp_verifies_and_opens_reset_form(): void
    {
        $user = $this->makeUser();
        $record = $this->makeOtp($user, '123456');

        $response = $this->post(route('password.otp.verify'), [
            'email' => $user->email,
            'otp' => '123456',
        ]);

        $response->assertRedirect(route('password.reset.form'));
        $response->assertSessionHas('password_otp_verified');

        $this->assertTrue(Hash::check('OldPassword1', $user->fresh()->password));
        $this->assertDatabaseMissing('password_reset_otps', ['id' => $record->id]);
    }

    public function test_reset_form_renders_after_verification(): void
    {
        $user = $this->makeUser();

        $this->withSession($this->verifiedSession($user))
            ->get(route('password.reset.form'))
            ->assertOk()
            ->assertSee(route('password.reset.update'), false)
            ->assertSee(__('Buat Password Baru'))
            ->assertSee('name="password"', false)
            ->assertSee('name="password_confirmation"', false);
    }

    public function test_correct_password_after_verification_resets_password(): void
    {
        Event::fake([PasswordReset::class]);

        $user = $this->makeUser();

        $response = $this->withSession($this->verifiedSession($user))
            ->post(route('password.reset.update'), [
                'password' => 'Raliva2026!',
                'password_confirmation' => 'Raliva2026!',
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $response->assertSessionMissing('password_otp_email');
        $response->assertSessionMissing('password_otp_verified');

        $this->assertTrue(Hash::check('Raliva2026!', $user->fresh()->password));
        Event::assertDispatched(PasswordReset::class);
    }

    public function test_reset_form_cannot_be_opened_without_verification(): void
    {
        $user = $this->makeUser();

        $this->withSession(['password_otp_email' => $user->email])
            ->get(route('password.reset.form'))
            ->assertRedirect(route('password.otp.form'))
            ->assertSessionHasErrors('otp');
    }

    public function test_expired_verification_session_is_rejected(): void
    {
        $user = $this->makeUser();

        $this->withSession([
            'password_otp_email' => $user->email,
            'password_otp_verified' => [
                'email' => $user->email,
                'expires_at' => now()->subMinute()->timestamp,
            ],
        ])->get(route('password.reset.form'))
            ->assertRedirect(route('password.otp.form'))
            ->assertSessionHasErrors('otp');
    }

    public function test_direct_password_reset_without_verification_is_rejected(): void
    {
        $user = $this->makeUser();

        $response = $this->post(route('password.reset.update'), [
            'password' => 'Raliva2026!',
            'password_confirmation' => 'Raliva2026!',
        ]);

        $response->assertRedirect(route('password.otp.form'));
        $response->assertSessionHasErrors('otp');

        $this->assertTrue(Hash::check('OldPassword1', $user->fresh()->password));
    }

    public function test_wrong_otp_is_rejected_and_increments_attempts(): void
    {
        $user = $this->makeUser();
        $record = $this->makeOtp($user, '123456');

        $response = $this->post(route('password.otp.verify'), [
            'email' => $user->email,
            'otp' => '000000',
        ]);

        $response->assertRedirect(route('password.otp.form'));
        $response->assertSessionHasErrors('otp');

        $this->assertTrue(Hash::check('OldPassword1', $user->fresh()->password));
        $this->assertSame(1, $record->fresh()->attempts);
    }

    public function test_expired_otp_is_rejected_and_removed(): void
    {
        $user = $this->makeUser();
        $record = $this->makeOtp($user, '123456', ['expires_at' => now()->subMinute()]);

        $response = $this->post(route('password.otp.verify'), [
            'email' => $user->email,
            'otp' => '123456',
        ]);

        $response->assertRedirect(route('password.otp.form'));
        $response->assertSessionHasErrors('otp');

        $this->assertTrue(Hash::check('OldPassword1', $user->fresh()->password));
        $this->assertDatabaseMissing('password_reset_otps', ['id' => $record->id]);
    }

    public function test_otp_with_max_attempts_is_rejected(): void
    {
        $user = $this->makeUser();
        $this->makeOtp($user, '123456', ['attempts' => 5]);

        $response = $this->post(route('password.otp.verify'), [
            'email' => $user->email,
            'otp' => '123456',
        ]);

        $response->assertRedirect(route('password.otp.form'));
        $response->assertSessionHasErrors('otp');

        $this->assertTrue(Hash::check('OldPassword1', $user->fresh()->password));
    }

    public function test_password_policy_is_enforced_on_reset_step(): void
    {
        $user = $this->makeUser();

        $response = $this->withSession($this->verifiedSession($user))
            ->post(route('password.reset.update'), [
                'password' => 'weakpassword',
                'password_confirmation' => 'weakpassword',
            ]);

        $response->assertSessionHasErrors('password');

        $this->assertTrue(Hash::check('OldPassword1', $user->fresh()->password));
    }

    public function test_resend_is_rate_limited_by_cooldown(): void
    {
        Mail::fake();

        $user = $this->makeUser();

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect(route('password.otp.form'));

        Mail::assertSentTimes(ResetPasswordOtpMail::class, 1);

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect(route('password.otp.form'))
            ->assertSessionHasErrors('email');

        Mail::assertSentTimes(ResetPasswordOtpMail::class, 1);
    }

    public function test_full_otp_flow_verifies_then_resets_password(): void
    {
        Mail::fake();
        Event::fake([PasswordReset::class]);

        $user = $this->makeUser();

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect(route('password.otp.form'));

        $otp = null;

        Mail::assertSent(ResetPasswordOtpMail::class, function (ResetPasswordOtpMail $mail) use (&$otp) {
            $otp = $mail->otp;

            return true;
        });

        $this->post(route('password.otp.verify'), [
            'email' => $user->email,
            'otp' => $otp,
        ])->assertRedirect(route('password.reset.form'));

        $this->post(route('password.reset.update'), [
            'password' => 'Raliva2026!',
            'password_confirmation' => 'Raliva2026!',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('Raliva2026!', $user->fresh()->password));
        Event::assertDispatched(PasswordReset::class);
    }

    public function test_old_reset_link_routes_are_removed(): void
    {
        $this->get('/reset-password/dummy-token')->assertNotFound();
        $this->post('/reset-password', [])->assertNotFound();
    }

    public function test_otp_email_contains_the_code(): void
    {
        $user = $this->makeUser();
        $mailable = new ResetPasswordOtpMail($user, '654321', now()->addMinutes(10));

        $mailable->assertSeeInHtml('654321');
        $mailable->assertSeeInHtml(__('Kode OTP Reset Password'));
        $mailable->assertSeeInHtml('NEXPRINT');
    }
}
