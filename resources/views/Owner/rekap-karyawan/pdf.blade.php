<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Karyawan {{ $storeName }} - Raliva</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1B1C1C; }

        .sheet { max-width: 780px; margin: 0 auto; }

        .head { border-bottom: 3px solid #8B1E3F; padding-bottom: 14px; margin-bottom: 20px; }
        .head-row { display: table; width: 100%; }
        .head-left, .head-right { display: table-cell; vertical-align: top; }
        .brand { font-size: 10px; font-weight: bold; letter-spacing: 3px; text-transform: uppercase; color: #8B1E3F; }
        h1 { font-size: 20px; color: #1B1C1C; margin: 4px 0 0; }
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

        @php
            $nullLbl = '—';
            $pct = fn ($v) => $v !== null ? number_format($v, 2, ',', '.').'%' : $nullLbl;
            $rp = fn ($v) => $v !== null ? $fmt($v) : $nullLbl;
        @endphp
        <div class="section-title"><span class="bar"></span>Ringkasan <small>{{ ucfirst($roleFilter) }}{{ !empty($dari) || !empty($sampai) ? ' • '.($dari ?? 'awal').' s/d '.($sampai ?? 'sekarang') : '' }}</small></div>
        <table class="kpi">
            <tr>
                @if (($roleFilter ?? 'semua') === 'admin')
                    <td style="width:25%;"><span class="lbl">Rata-rata CR</span><span class="val">{{ $pct($totals['cr'] ?? null) }}</span></td>
                    <td style="width:25%;"><span class="lbl">Rata-rata AOV</span><span class="val">{{ $rp($totals['aov'] ?? null) }}</span></td>
                    <td style="width:25%;"><span class="lbl">Rata-rata Rating</span><span class="val">{{ ($totals['rating'] ?? null) !== null ? number_format($totals['rating'], 1).' ★' : $nullLbl }}</span></td>
                    <td style="width:25%;"><span class="lbl">Pesanan</span><span class="val">{{ number_format($totals['pesanan'] ?? 0, 0, ',', '.') }}</span></td>
                @elseif (($roleFilter ?? 'semua') === 'produksi')
                    <td style="width:25%;"><span class="lbl">Ditugaskan</span><span class="val">{{ number_format($totals['ditugaskan'] ?? 0, 0, ',', '.') }}</span></td>
                    <td style="width:25%;"><span class="lbl">Selesai</span><span class="val">{{ number_format($totals['selesai'] ?? 0, 0, ',', '.') }}</span></td>
                    <td style="width:25%;"><span class="lbl">Keberhasilan</span><span class="val">{{ $pct($totals['sukses_persen'] ?? null) }}</span></td>
                    <td style="width:25%;"><span class="lbl">Karyawan</span><span class="val">{{ count($rows) }}</span></td>
                @else
                    <td style="width:25%;"><span class="lbl">Transfer Diminta</span><span class="val">{{ number_format($totals['transfer_diminta'] ?? 0, 0, ',', '.') }}</span></td>
                    <td style="width:25%;"><span class="lbl">Transfer Selesai</span><span class="val">{{ number_format($totals['transfer_selesai'] ?? 0, 0, ',', '.') }}</span></td>
                    <td style="width:25%;"><span class="lbl">Akurasi Opname</span><span class="val">{{ $pct($totals['akurasi_persen'] ?? null) }}</span></td>
                    <td style="width:25%;"><span class="lbl">Unit Rusak</span><span class="val">{{ number_format($totals['kerusakan_qty'] ?? 0, 0, ',', '.') }}</span></td>
                @endif
            </tr>
        </table>

        <div class="section-title"><span class="bar"></span>Rekap per Karyawan <small>{{ ucfirst($roleFilter ?? 'semua') }}</small></div>
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th style="width:28px;">No</th>
                        <th>Karyawan</th>
                        @if (($roleFilter ?? 'admin') === 'admin')
                            <th class="r">CR</th><th class="r">AOV</th><th class="r">Rating</th><th class="r">Pesanan</th>
                        @elseif (($roleFilter ?? 'admin') === 'produksi')
                            <th class="r">Ditugaskan</th><th class="r">Rata2 Unit</th><th class="r">Rata2 Durasi</th><th class="r">Berhasil</th>
                        @else
                            <th class="r">Transfer</th><th class="r">Rata2 Putaran</th><th class="r">Akurasi</th><th class="r">Rusak</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $i => $r)
                        <tr>
                            <td class="fw" style="text-align:center;">{{ $i + 1 }}</td>
                            <td class="fw">{{ $r['nama'] }}</td>
                            @if (($roleFilter ?? 'admin') === 'admin')
                                <td class="r">{{ $pct($r['cr'] ?? null) }}</td>
                                <td class="r fw">{{ $rp($r['aov'] ?? null) }}</td>
                                <td class="r">{{ ($r['rating'] ?? null) !== null ? number_format($r['rating'], 1).' ★ ('.$r['rating_count'].')' : $nullLbl }}</td>
                                <td class="r">{{ number_format($r['pesanan'] ?? 0, 0, ',', '.') }}</td>
                            @elseif (($roleFilter ?? 'admin') === 'produksi')
                                <td class="r">{{ number_format($r['ditugaskan'] ?? 0, 0, ',', '.') }}</td>
                                <td class="r">{{ ($r['rata_unit_diminta'] ?? null) !== null ? number_format($r['rata_unit_diminta'], 1, ',', '.') : $nullLbl }}</td>
                                <td class="r">{{ ($r['rata_durasi_jam'] ?? null) !== null ? number_format($r['rata_durasi_jam'], 1, ',', '.').' jam' : $nullLbl }}</td>
                                <td class="r fw">{{ $pct($r['sukses_persen'] ?? null) }}</td>
                            @else
                                <td class="r">{{ number_format($r['transfer_diminta'] ?? 0, 0, ',', '.') }}</td>
                                <td class="r">{{ ($r['rata_putaran_jam'] ?? null) !== null ? number_format($r['rata_putaran_jam'], 1, ',', '.').' jam' : $nullLbl }}</td>
                                <td class="r fw">{{ $pct($r['akurasi_persen'] ?? null) }}</td>
                                <td class="r refund">{{ number_format($r['kerusakan_qty'] ?? 0, 0, ',', '.') }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center; color:#9a9c9c;">Belum ada data karyawan.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td>Total</td>
                        @if (($roleFilter ?? 'admin') === 'admin')
                            <td class="r">{{ $pct($totals['cr'] ?? null) }}</td>
                            <td class="r">{{ $rp($totals['aov'] ?? null) }}</td>
                            <td class="r">{{ ($totals['rating'] ?? null) !== null ? number_format($totals['rating'], 1).' ★' : $nullLbl }}</td>
                            <td class="r">{{ number_format($totals['pesanan'] ?? 0, 0, ',', '.') }}</td>
                        @elseif (($roleFilter ?? 'admin') === 'produksi')
                            <td class="r">{{ number_format($totals['ditugaskan'] ?? 0, 0, ',', '.') }}</td>
                            <td class="r">—</td>
                            <td class="r">—</td>
                            <td class="r">{{ $pct($totals['sukses_persen'] ?? null) }}</td>
                        @else
                            <td class="r">{{ number_format($totals['transfer_diminta'] ?? 0, 0, ',', '.') }}</td>
                            <td class="r">—</td>
                            <td class="r">{{ $pct($totals['akurasi_persen'] ?? null) }}</td>
                            <td class="r">{{ number_format($totals['kerusakan_qty'] ?? 0, 0, ',', '.') }}</td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="footer">
            @if (($roleFilter ?? 'admin') === 'admin')
                Closing Rate = pembayaran sukses yang ditangani / seluruh pembayaran yang ditangani. Rating adalah proxy dari ulasan pada order yang diverifikasi karyawan.
            @elseif (($roleFilter ?? 'admin') === 'produksi')
                Metrik dihitung dari production order yang ditugaskan (assigned_to). Durasi hanya dari order selesai bertanggal valid.
            @else
                Transfer diatribusikan ke peminta. Akurasi = opname tanpa selisih / total opname.
            @endif
            Dokumen ini dihasilkan otomatis oleh sistem Raliva &mdash; {{ $storeName }} &mdash; {{ now()->translatedFormat('d M Y') }}
        </div>
    </div>
</body>
</html>