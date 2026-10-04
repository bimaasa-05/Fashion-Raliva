<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>{{ __('Sesi Berakhir') }} — Raliva</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', system-ui, sans-serif; background: #fbf9f9; color: #1b1c1c;
        min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
    .card { background: #fff; border: 1px solid #e5e1da; border-radius: 16px; padding: 40px 32px;
        max-width: 440px; width: 100%; text-align: center; box-shadow: 0 12px 32px -16px rgb(17 17 17 / .16); }
    .icon { font-size: 48px; line-height: 1; margin-bottom: 16px; }
    h1 { font-size: 22px; margin-bottom: 12px; }
    p { font-size: 14px; color: #555; line-height: 1.6; margin-bottom: 24px; }
    a.btn { display: inline-block; background: #8B1E3F; color: #fff; text-decoration: none;
        padding: 12px 32px; border-radius: 12px; font-weight: 700; font-size: 13px; letter-spacing: .08em; }
    a.btn:hover { background: #6D1428; }
    .back { display: block; margin-top: 16px; font-size: 13px; color: #8B1E3F; }
</style>
</head>
<body>
    <div class="card">
        <div class="icon">⏱️</div>
        <h1>{{ __('Sesi Anda Telah Berakhir') }}</h1>
        <p>{{ __('Anda terlalu lama tidak beraktivitas sehingga halaman ini kedaluwarsa.') }}<br/>{{ __('Jangan khawatir — silakan masuk kembali, lalu ulangi tindakan terakhir Anda.') }}</p>
        <a class="btn" href="{{ route('login', ['expired' => 1]) }}">{{ __('MASUK KEMBALI') }}</a>
        <a class="back" href="javascript:history.back()">← {{ __('Kembali ke halaman sebelumnya') }}</a>
    </div>
</body>
</html>
