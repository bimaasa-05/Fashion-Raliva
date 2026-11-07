<?php

namespace App\Mail;

use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $otp,
        public CarbonInterface $expiresAt,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Kode OTP Reset Password').' - '.brand(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password-otp',
            with: [
                'userName' => $this->user->nama_lengkap,
                'otp' => $this->otp,
                'expiresAt' => $this->expiresAt,
                'validMinutes' => max(1, (int) ceil(($this->expiresAt->timestamp - now()->timestamp) / 60)),
            ],
        );
    }
}
