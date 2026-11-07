<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Kode OTP Reset Password') }}</title>
</head>

<body style="margin:0;padding:0;background:#f6f3ef;font-family:Arial,Helvetica,sans-serif;color:#1c1b1b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="background:#f6f3ef;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:520px;background:#ffffff;border:1px solid #e5ded6;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td style="background:#1c1b1b;padding:24px 32px;">
                            <p style="margin:0;font-size:20px;font-weight:700;color:#ffffff;">{{ brand() }}</p>
                            <p
                                style="margin:6px 0 0;font-size:12px;letter-spacing:2px;text-transform:uppercase;color:#d6b36a;">
                                {{ __('RESET PASSWORD') }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <h1 style="margin:0 0 12px;font-size:24px;line-height:1.3;color:#1c1b1b;">
                                {{ __('Kode OTP Reset Password') }}
                            </h1>
                            <p style="margin:0 0 16px;font-size:15px;line-height:1.6;color:#4a4640;">
                                {{ __('Halo, :name.', ['name' => $userName]) }}
                            </p>
                            <p style="margin:0 0 24px;font-size:15px;line-height:1.6;color:#4a4640;">
                                {{ __('Gunakan kode OTP berikut untuk mengatur ulang password akun :brand Anda.', ['brand' => brand()]) }}
                            </p>
                            <div style="text-align:center;margin:0 0 24px;">
                                <span
                                    style="display:inline-block;padding:16px 28px;border:2px solid #A32626;border-radius:12px;background:#fdf7f2;font-size:32px;letter-spacing:10px;font-weight:700;color:#A32626;">{{ $otp }}</span>
                            </div>
                            <p style="margin:0 0 8px;font-size:14px;line-height:1.6;color:#4a4640;">
                                {{ __('Kode ini berlaku selama :minutes menit.', ['minutes' => $validMinutes]) }}
                            </p>
                            <p style="margin:0 0 24px;font-size:14px;line-height:1.6;color:#4a4640;">
                                {{ __('Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.') }}
                            </p>
                            <p style="margin:0;font-size:13px;line-height:1.6;color:#8a8781;">
                                {{ __('Demi keamanan, jangan bagikan kode ini kepada siapa pun.') }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 32px;background:#fbf9f9;border-top:1px solid #e5ded6;">
                            <p style="margin:0;font-size:12px;color:#8a8781;">
                                &copy; {{ date('Y') }} {{ brand() }}. {{ __('Semua hak dilindungi.') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
