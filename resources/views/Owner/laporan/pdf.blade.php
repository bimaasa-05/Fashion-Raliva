<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Toko {{ $store?->nama_toko ?? '' }} - Raliva</title>
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
        .refund { color: #BA1A1A; }
        tfoot td { background: #FBF8F2; border: 1px solid #D0C9BC; font-weight: bold; }
        .top td:first-child { width: 32px; text-align: center; color: #8B1E3F; font-weight: bold; font-size: 13px; }

        .footer { border-top: 2px solid #E5E0D6; margin-top: 20px; padding-top: 8px; font-size: 9px; color: #9a9c9c; }
    </style>
</head>
<body>
    <table class="head" style="border-collapse:collapse;">
        <tr>
            <td style="border:none;padding:0;">
                <div class="brand">Raliva</div>
                <h1>Laporan Toko</h1>
                <div class="store">{{ $store?->nama_toko ?? 'Toko Saya' }}</div>
            </td>
            <td class="meta" style="border:none;padding:0;text-align:right;">
                <div>Periode: <strong>{{ $periodeLabel }}</strong></div>
                <div>Sampai: <strong>{{ now()->translatedFormat('d M Y') }}</strong></div>
                <div>Dicetak: {{ now()->translatedFormat('d M Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title"><span class="bar"></span>Ringkasan <small>Akumulasi</small></div>
    <table class="kpi" style="width:100%;border-collapse:collapse;">
        <tr>
            <td style="width:25%;"><span class="lbl">Pendapatan Bersih</span><span class="val">{{ $fmt($pendapatan) }}</span><span class="hnt">total order selesai</span></td>
            <td style="width:25%;"><span class="lbl">Pesanan Selesai</span><span class="val">{{ number_format($pesananSelesai, 0, ',', '.') }}</span><span class="hnt">akumulasi</span></td>
            <td style="width:25%;"><span class="lbl">Nilai Refund</span><span class="val">{{ $fmt($refund) }}</span><span class="hnt">refund selesai</span></td>
            <td style="width:25%;"><span class="lbl">Dana Dicairkan</span><span class="val">{{ $fmt($dicairkan) }}</span><span class="hnt">withdrawal selesai</span></td>
        </tr>
    </table>

    <div class="section-title"><span class="bar"></span>Laporan Periode <small>{{ $periodeLabel }}</small></div>
    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th class="r">Pesanan</th>
                <th class="r">Pendapatan</th>
                <th class="r">Refund</th>
                <th class="r">Pencairan</th>
                <th class="r">Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report as $row)
                <tr>
                    <td class="fw">{{ $row['periode'] }}</td>
                    <td class="r">{{ number_format($row['pesanan'], 0, ',', '.') }}</td>
                    <td class="r fw">{{ $fmt($row['pendapatan']) }}</td>
                    <td class="r refund">{{ $fmt($row['refund']) }}</td>
                    <td class="r">{{ $fmt($row['pencairan']) }}</td>
                    <td class="r fw">{{ $fmt($row['pendapatan'] - $row['refund'] - $row['pencairan']) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td class="r">{{ number_format($totals['pesanan'], 0, ',', '.') }}</td>
                <td class="r">{{ $fmt($totals['pendapatan']) }}</td>
                <td class="r refund">{{ $fmt($totals['refund']) }}</td>
                <td class="r">{{ $fmt($totals['pencairan']) }}</td>
                <td class="r">{{ $fmt($totals['pendapatan'] - $totals['refund'] - $totals['pencairan']) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="section-title"><span class="bar"></span>Produk Terlaris <small>Top 5</small></div>
    <table class="top">
        <thead>
            <tr>
                <th style="width:32px;">No</th>
                <th>Produk</th>
                <th class="r">Terjual (pcs)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($top as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p['nama'] }}</td>
                    <td class="r fw">{{ number_format($p['terjual'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan otomatis oleh sistem Raliva &mdash; {{ $store?->nama_toko ?? '' }} &mdash; {{ now()->translatedFormat('d M Y') }}
    </div>
</body>
</html>