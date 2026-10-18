<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Laporan Operasional') }} - {{ brand() }}</title>
    <style>
        @page { size: A4; margin: 16mm 14mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1B1C1C; }
        * { box-sizing: border-box; }

        .head { border-bottom: 3px solid #8B1E3F; padding-bottom: 14px; margin-bottom: 20px; }
        .brand { font-size: 10px; font-weight: bold; letter-spacing: 3px; text-transform: uppercase; color: #8B1E3F; }
        h1 { margin: 4px 0 0; font-size: 22px; color: #1B1C1C; }
        .store { font-size: 12px; color: #5c5f5f; margin-top: 4px; }
        .meta { text-align: right; font-size: 10px; color: #5c5f5f; line-height: 1.7; }
        .meta strong { color: #1B1C1C; }

        .section-title { font-size: 13px; font-weight: bold; margin: 22px 0 10px; color: #1B1C1C; }
        .section-title .bar { display: inline-block; width: 4px; height: 13px; background: #8B1E3F; margin-right: 6px; vertical-align: -1px; }
        .section-title small { font-weight: normal; font-size: 9px; color: #5c5f5f; text-transform: uppercase; letter-spacing: 1px; }

        table { width: 100%; border-collapse: collapse; }
        .kpi td { border: 1px solid #E5E0D6; background: #FBF8F2; padding: 9px 12px; }
        .kpi .lbl { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #7a7d7d; display: block; }
        .kpi .val { font-size: 14px; font-weight: bold; color: #1B1C1C; display: block; margin-top: 4px; }
        .kpi .hnt { font-size: 8px; color: #9a9c9c; display: block; margin-top: 3px; }

        th { background: #F7EFE3; border: 1px solid #D0C9BC; padding: 7px 9px; text-align: left; font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px; color: #1B1C1C; }
        th.r, td.r { text-align: right; }
        td { border: 1px solid #E5E0D6; padding: 7px 9px; font-size: 10.5px; }
        td.fw { font-weight: bold; }
        .expense { color: #BA1A1A; }
        tfoot td { background: #FBF8F2; border: 1px solid #D0C9BC; font-weight: bold; }

        .footer { border-top: 2px solid #E5E0D6; margin-top: 20px; padding-top: 8px; font-size: 9px; color: #9a9c9c; }
    </style>
</head>
<body>
    <table class="head" style="border-collapse:collapse;">
        <tr>
            <td style="border:none;padding:0;">
                <div class="brand">{{ brand() }}</div>
                <h1>{{ __('Laporan Operasional') }}</h1>
                <div class="store">{{ $laporan->labelToko() }}</div>
            </td>
            <td class="meta" style="border:none;padding:0;text-align:right;">
                <div>{{ __('Periode:') }} <strong>{{ $laporan->labelPeriode() }}</strong></div>
                <div>{{ __('Dicetak:') }} {{ now()->translatedFormat('d M Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title"><span class="bar"></span>{{ __('Ringkasan') }} <small>{{ $laporan->labelPeriode() }}</small></div>
    <table class="kpi" style="width:100%;border-collapse:collapse;">
        <tr>
            <td style="width:25%;"><span class="lbl">{{ __('Total Pendapatan') }}</span><span class="val">{{ $fmt($ringkasan['pendapatan']) }}</span><span class="hnt">{{ __('pesanan berstatus pendapatan') }}</span></td>
            <td style="width:25%;"><span class="lbl">{{ __('Pesanan Berpendapatan') }}</span><span class="val">{{ number_format($ringkasan['pesanan'], 0, ',', '.') }}</span><span class="hnt">{{ __('pesanan') }}</span></td>
            <td style="width:25%;"><span class="lbl">{{ __('Total Pengeluaran') }}</span><span class="val">{{ $fmt($ringkasan['pengeluaran']) }}</span><span class="hnt">{{ __('refund + pengeluaran toko') }}</span></td>
            <td style="width:25%;"><span class="lbl">{{ __('Total Bersih') }}</span><span class="val">{{ $fmt($ringkasan['bersih']) }}</span><span class="hnt">{{ __('pendapatan - pengeluaran') }}</span></td>
        </tr>
    </table>

    <div class="section-title"><span class="bar"></span>{{ __('Rincian Harian') }} <small>{{ $laporan->labelPeriode() }}</small></div>
    <table>
        <thead>
            <tr>
                <th>{{ __('Tanggal') }}</th>
                <th class="r">{{ __('Pesanan') }}</th>
                <th class="r">{{ __('Pendapatan') }}</th>
                <th class="r">Refund</th>
                <th class="r">{{ __('Pengeluaran') }}</th>
                <th class="r">{{ __('Bersih') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($harian as $row)
                <tr>
                    <td class="fw">{{ $row['tanggal']->translatedFormat('d M Y') }}</td>
                    <td class="r">{{ number_format($row['pesanan'], 0, ',', '.') }}</td>
                    <td class="r fw">{{ $fmt($row['pendapatan']) }}</td>
                    <td class="r expense">{{ $fmt($row['refund']) }}</td>
                    <td class="r expense">{{ $fmt($row['expense']) }}</td>
                    <td class="r fw">{{ $fmt($row['bersih']) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>{{ __('Total') }}</td>
                <td class="r">{{ number_format($ringkasan['pesanan'], 0, ',', '.') }}</td>
                <td class="r">{{ $fmt($ringkasan['pendapatan']) }}</td>
                <td class="r expense">{{ $fmt($ringkasan['refund']) }}</td>
                <td class="r expense">{{ $fmt($ringkasan['expense']) }}</td>
                <td class="r">{{ $fmt($ringkasan['bersih']) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="section-title"><span class="bar"></span>{{ __('Metode Pembayaran') }}</div>
    <table>
        <thead>
            <tr>
                <th>{{ __('Metode') }}</th>
                <th class="r">{{ __('Transaksi') }}</th>
                <th class="r">{{ __('Total') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($perMetode as $m)
                <tr>
                    <td class="fw">{{ $m->nama_metode }}</td>
                    <td class="r">{{ number_format($m->jumlah_transaksi, 0, ',', '.') }}</td>
                    <td class="r fw">{{ $fmt($m->total) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align:center;color:#9a9c9c;">{{ __('Belum ada pembayaran terverifikasi pada periode ini.') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title"><span class="bar"></span>{{ __('Pesanan Selesai') }} <small>{{ $laporan->labelPeriode() }}</small></div>
    <table>
        <thead>
            <tr>
                <th>{{ __('Nomor Order') }}</th>
                <th>{{ __('Tanggal') }}</th>
                <th>{{ __('Customer') }}</th>
                <th>{{ __('Metode Bayar') }}</th>
                <th class="r">{{ __('Total') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesananSelesai as $p)
                <tr>
                    <td class="fw">{{ $p->nomor_order ?? ('#'.$p->order_id) }}</td>
                    <td>{{ $p->created_at?->translatedFormat('d M Y') ?? '-' }}</td>
                    <td>{{ $p->checkout?->user?->nama_lengkap ?? '-' }}</td>
                    <td>{{ $p->checkout?->payment?->paymentMethod?->nama_metode ?? '-' }}</td>
                    <td class="r fw">{{ $fmt($p->grand_total) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:#9a9c9c;">{{ __('Belum ada pesanan selesai pada periode ini.') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        {{ __('Dokumen ini dihasilkan otomatis oleh sistem :brand', ['brand' => brand()]) }} &mdash; {{ $laporan->labelToko() }} &mdash; {{ now()->translatedFormat('d M Y') }}
    </div>
</body>
</html>
