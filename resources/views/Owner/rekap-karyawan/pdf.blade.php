<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Karyawan {{ $storeName }} - Raliva</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1B1C1C; }

        .sheet { max-width: 780px; margin: 0 auto; }

        .head { border-bottom: 3px solid #C9A24D; padding-bottom: 14px; margin-bottom: 20px; }
        .head-row { display: table; width: 100%; }
        .head-left, .head-right { display: table-cell; vertical-align: top; }
        .brand { font-size: 10px; font-weight: bold; letter-spacing: 3px; text-transform: uppercase; color: #C9A24D; }
        h1 { font-size: 20px; color: #1B1C1C; margin: 4px 0 0; }
        .store { font-size: 12px; color: #5c5f5f; margin-top: 4px; }
        .meta { text-align: right; font-size: 10px; color: #5c5f5f; line-height: 1.7; }
        .meta strong { color: #1B1C1C; }

        .section-title { font-size: 13px; font-weight: bold; margin: 22px 0 10px; color: #1B1C1C; }
        .section-title .bar { display: inline-block; width: 4px; height: 13px; background: #C9A24D; margin-right: 6px; vertical-align: -1px; }
        .section-title small { font-weight: normal; font-size: 9px; color: #5c5f5f; text-transform: uppercase; letter-spacing: 1px; }

        table { width: 100%; border-collapse: collapse; }
        .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .kpi td { border: 1px solid #E5E0D6; background: #FBF8F2; padding: 9px 12px; }
        .kpi .lbl { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #7a7d7d; display: block; }
        .kpi .val { font-size: 13px; font-weight: bold; color: #1B1C1C; display: block; margin-top: 4px; }
        .kpi .hnt { font-size: 8px; color: #9a9c9c; display: block; margin-top: 3px; }

        th { background: #F7EFE3; border: 1px solid #D0C9BC; padding: 7px 9px; text-align: left; font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px; color: #1B1C1C; }
        th.r, td.r { text-align: right; }
        td { border: 1px solid #E5E0D6; padding: 7px 9px; font-size: 10.5px; }
        td.fw { font-weight: bold; }
        .refund { color: #BA1A1A; }
        tfoot td { background: #FBF8F2; border: 1px solid #D0C9BC; font-weight: bold; }

        .footer { border-top: 2px solid #E5E0D6; margin-top: 20px; padding-top: 8px; font-size: 9px; color: #9a9c9c; }

        @page { size: A4; margin: 16mm 14mm; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="head">
            <div class="head-row">
                <div class="head-left">
                    <div class="brand">Raliva</div>
                    <h1>Rekap Karyawan</h1>
                    <div class="store">{{ $storeName }}</div>
                </div>
                <div class="head-right meta">
                    <div>Sampai: <strong>{{ now()->translatedFormat('d M Y') }}</strong></div>
                    <div>Dicetak: {{ now()->translatedFormat('d M Y H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="section-title"><span class="bar"></span>Ringkasan <small>Total Semua Karyawan</small></div>
        <table class="kpi">
            <tr>
                <td style="width:25%;"><span class="lbl">Total Pendapatan</span><span class="val">{{ $fmt($totals['pendapatan']) }}</span></td>
                <td style="width:25%;"><span class="lbl">Total Pengeluaran</span><span class="val">{{ $fmt($totals['pengeluaran']) }}</span></td>
                <td style="width:25%;"><span class="lbl">Total Bersih</span><span class="val">{{ $fmt($totals['bersih']) }}</span></td>
                <td style="width:25%;"><span class="lbl">Pesanan</span><span class="val">{{ number_format($totals['pesanan'], 0, ',', '.') }}</span></td>
            </tr>
        </table>

        <div class="section-title"><span class="bar"></span>Rekap per Karyawan <small>By Pendapatan</small></div>
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th style="width:28px;">No</th>
                        <th>Karyawan</th>
                        <th class="r">Pesanan</th>
                        <th class="r">Pendapatan</th>
                        <th class="r">Pengeluaran</th>
                        <th class="r">Bersih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $i => $r)
                        <tr>
                            <td class="fw" style="text-align:center;">{{ $i + 1 }}</td>
                            <td class="fw">{{ $r['nama'] }}</td>
                            <td class="r">{{ number_format($r['pesanan'], 0, ',', '.') }}</td>
                            <td class="r fw">{{ $fmt($r['pendapatan']) }}</td>
                            <td class="r refund">{{ $fmt($r['pengeluaran']) }}</td>
                            <td class="r fw">{{ $fmt($r['bersih']) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center; color:#9a9c9c;">Belum ada data karyawan.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td>Total</td>
                        <td class="r">{{ number_format($totals['pesanan'], 0, ',', '.') }}</td>
                        <td class="r">{{ $fmt($totals['pendapatan']) }}</td>
                        <td class="r refund">{{ $fmt($totals['pengeluaran']) }}</td>
                        <td class="r">{{ $fmt($totals['bersih']) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="footer">
            Pendapatan per karyawan dihitung dari order yang pembayarannya diverifikasi karyawan tersebut.
            Dokumen ini dihasilkan otomatis oleh sistem Raliva &mdash; {{ $storeName }} &mdash; {{ now()->translatedFormat('d M Y') }}
        </div>
    </div>
</body>
</html>