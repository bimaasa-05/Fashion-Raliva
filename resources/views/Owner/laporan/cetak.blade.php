<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Toko {{ $store?->nama_toko ?? '' }} - Raliva</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; color: #1B1C1C; background: #F3F1EC; }

        .print-bar {
            position: sticky; top: 0; z-index: 50;
            display: flex; align-items: center; justify-content: space-between; gap: 14px;
            padding: 14px 20px;
            background: #1B1C1C; color: #fff;
        }
        .print-bar .bar-text { display: flex; flex-direction: column; min-width: 0; }
        .print-bar .bar-overline {
            font-size: 10px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;
            color: #8B1E3F;
        }
        .print-bar .bar-title { font-size: 18px; font-weight: 700; letter-spacing: 0.3px; margin-top: 2px; }
        .print-bar .bar-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .print-bar button, .print-bar a {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 600;
            cursor: pointer; text-decoration: none; border: none;
        }
        .btn-print { background: #8B1E3F; color: #1B1C1C; }
        .btn-back { background: #fff; color: #1B1C1C; }

        .sheet { max-width: 810px; margin: 20px auto; background: #fff; padding: 40px 34px; box-shadow: 0 1px 6px rgba(0,0,0,0.12); }

        .head { border-bottom: 3px solid #8B1E3F; padding-bottom: 14px; margin-bottom: 20px; }
        .head-row { display: table; width: 100%; }
        .head-left, .head-right { display: table-cell; vertical-align: top; }
        .brand { font-size: 10px; font-weight: bold; letter-spacing: 3px; text-transform: uppercase; color: #8B1E3F; }
        h1 { font-size: 22px; color: #1B1C1C; margin: 4px 0 0; }
        .store { font-size: 12px; color: #5c5f5f; margin-top: 4px; }
        .meta { text-align: right; font-size: 10px; color: #5c5f5f; line-height: 1.7; }
        .meta strong { color: #1B1C1C; }

        .section-title { font-size: 13px; font-weight: bold; margin: 22px 0 10px; color: #1B1C1C; }
        .section-title .bar { display: inline-block; width: 4px; height: 13px; background: #8B1E3F; margin-right: 6px; vertical-align: -1px; }
        .section-title small { font-weight: normal; font-size: 9px; color: #5c5f5f; text-transform: uppercase; letter-spacing: 1px; }

        table { width: 100%; border-collapse: collapse; }
        .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
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

        @media print {
            body { background: #fff; }
            .print-bar { display: none; }
            .sheet { max-width: none; margin: 0; padding: 0; box-shadow: none; }
            @page { size: A4; margin: 16mm 14mm; }
        }

        @media (max-width: 640px) {
            .print-bar { flex-direction: column; align-items: stretch; gap: 10px; padding: 12px 14px; }
            .print-bar .bar-actions { flex-direction: column; width: 100%; }
            .print-bar button, .print-bar a { width: 100%; justify-content: center; padding: 10px 16px; }
            .sheet { margin: 10px 8px; padding: 24px 16px; }
            .head-row { display: block; }
            .head-right { display: block; text-align: left; margin-top: 10px; }
            .kpi tr { display: grid; grid-template-columns: 1fr 1fr; }
            .kpi td { width: auto !important; }
            .top td:first-child { width: 28px; }
        }
    </style>
</head>
<body>
    <div class="print-bar">
        <div class="bar-text">
            <div class="bar-overline">Pratinjau Laporan</div>
            <div class="bar-title">Laporan Toko</div>
        </div>
        <div class="bar-actions">
            @php $cetakNoStore = ! \App\Support\OwnerContext::currentStore(); @endphp
            <a href="{{ route('owner.laporan.export-pdf', ['period' => $period]) }}" @if($cetakNoStore) aria-disabled="true" tabindex="-1" title="Ajukan toko dulu" @endif class="btn-print" @if($cetakNoStore) style="opacity:.6;pointer-events:none;" @endif>
                &#128424; Cetak PDF
            </a>
            <a href="{{ route('owner.laporan', ['period' => $period]) }}" class="btn-back" onclick="window.close();">&larr; Kembali</a>
        </div>
    </div>

    <div class="sheet">
        <div class="head">
            <div class="head-row">
                <div class="head-left">
                    <div class="brand">Raliva</div>
                    <h1>Laporan Toko</h1>
                    <div class="store">{{ $store?->nama_toko ?? 'Toko Saya' }}</div>
                </div>
                <div class="head-right meta">
                    <div>Periode: <strong>{{ $periodeLabel }}</strong></div>
                    <div>Sampai: <strong>{{ now()->translatedFormat('d M Y') }}</strong></div>
                    <div>Dicetak: {{ now()->translatedFormat('d M Y H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="section-title"><span class="bar"></span>Ringkasan <small>Akumulasi</small></div>
        <table class="kpi">
            <tr>
                <td style="width:25%;"><span class="lbl">Pendapatan Bersih</span><span class="val">{{ $fmt($pendapatan) }}</span><span class="hnt">total order selesai</span></td>
                <td style="width:25%;"><span class="lbl">Pesanan Selesai</span><span class="val">{{ number_format($pesananSelesai, 0, ',', '.') }}</span><span class="hnt">akumulasi</span></td>
                <td style="width:25%;"><span class="lbl">Nilai Refund</span><span class="val">{{ $fmt($refund) }}</span><span class="hnt">refund selesai</span></td>
                <td style="width:25%;"><span class="lbl">Dana Dicairkan</span><span class="val">{{ $fmt($dicairkan) }}</span><span class="hnt">withdrawal selesai</span></td>
            </tr>
        </table>

        <div class="section-title"><span class="bar"></span>Laporan Periode <small>{{ $periodeLabel }}</small></div>
        <div class="table-scroll">
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
        </div>

        <div class="section-title"><span class="bar"></span>Produk Terlaris <small>Top 5</small></div>
        <div class="table-scroll">
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
        </div>

        <div class="footer">
            Dokumen ini dihasilkan otomatis oleh sistem Raliva &mdash; {{ $store?->nama_toko ?? '' }} &mdash; {{ now()->translatedFormat('d M Y') }}
        </div>
    </div>
</body>
</html>