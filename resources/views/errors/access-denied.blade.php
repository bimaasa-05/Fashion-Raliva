<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Manrope', system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-surface-dim text-on-surface antialiased" style="background:#dbdad9;color:#1b1c1c;">
    <div class="min-h-screen flex items-center justify-center px-6">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-outline-variant p-8 text-center"
             style="border-color:#e3e2e2;">
            <div class="mx-auto w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mb-5"
                 style="background:#fde8e8;">
                <span class="material-symbols-outlined text-[36px]" style="color:#8B1E1E;">gpp_maybe</span>
            </div>
            <h1 class="font-bold text-2xl mb-2" style="font-family:'Playfair Display',serif;">Akses Ditolak</h1>
            <p class="text-on-surface-variant text-sm leading-relaxed mb-6" style="color:#5f5f5f;">
                {{ $message ?? 'Anda tidak memiliki izin untuk membuka halaman ini. Silakan kembali ke beranda sesuai peran Anda.' }}
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route($homeRoute ?? 'login') }}"
                   class="inline-flex items-center justify-center gap-2 h-12 px-6 rounded-full font-semibold text-white"
                   style="background:#8B1E1E;">
                    <span class="material-symbols-outlined text-[20px]">home</span>
                    Kembali ke Beranda
                </a>
                <button type="button" onclick="history.back()"
                        class="inline-flex items-center justify-center gap-2 h-12 px-6 rounded-full font-semibold border"
                        style="border-color:#8B1E1E;color:#8B1E1E;">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Kembali
                </button>
            </div>
        </div>
    </div>
</body>
</html>
