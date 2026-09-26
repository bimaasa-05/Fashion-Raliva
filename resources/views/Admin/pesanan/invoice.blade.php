<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice {{ $pesanan->nomor_order ?? ('#'.$pesanan->order_id) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Manrope', Arial, sans-serif; color: #1c1b1b; background: #fff; padding: 32px; }
        .sheet { max-width: 760px; margin: 0 auto; }
        .kop { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #1c1b1b; padding-bottom: 16px; margin-bottom: 20px; }
        .kop h1 { font-size: 28px; letter-spacing: 4px; }
        .kop .toko { font-size: 14px; font-weight: bold; margin-top: 4px; }
        .kop .meta { text-align: right; font-size: 12px; color: #555; }
        .kop .meta strong { color: #1c1b1b; }
        .grid { display: flex; gap: 24px; margin-bottom: 20px; }
        .grid > div { flex: 1; font-size: 13px; }
        .grid h4 { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #777; margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px; }
        th { text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #777; border-bottom: 2px solid #1c1b1b; padding: 8px 6px; }
        td { border-bottom: 1px solid #e5e5e5; padding: 10px 6px; }
        .r { text-align: right; }
        .total-box { width: 280px; margin-left: auto; font-size: 13px; }
        .total-box div { display: flex; justify-content: space-between; padding: 4px 0; }
        .total-box .grand { font-weight: bold; font-size: 16px; border-top: 2px solid #1c1b1b; margin-top: 6px; padding-top: 8px; }
        .note { font-size: 12px; color: #555; margin-top: 16px; }
        .sign { display: flex; justify-content: space-between; margin-top: 48px; font-size: 13px; text-align: center; }
        .toolbar { max-width: 760px; margin: 0 auto 20px; display: flex; gap: 8px; }
        .toolbar button, .toolbar a { padding: 10px 18px; font-size: 13px; border-radius: 8px; cursor: pointer; text-decoration: none; }
        .btn-print { background: #1c1b1b; color: #fff; border: none; }
        .btn-back { background: #fff; color: #1c1b1b; border: 1px solid #ccc; }
        @media print {
            body { padding: 0; }
            .toolbar { display: none; }
        }
    </style>
</head>
<body>
<div class="sheet">
    <div class="toolbar">
        <button type="button" class="btn-print" onclick="window.print()">Cetak Invoice</button>
        <a href="{{ route('admin.pesanan') }}" class="btn-back">&larr; Kembali</a>
    </div>
    <div class="kop">
        <div>
            <h1>RALIVA</h1>
            <p class="toko">{{ $pesanan->store?->nama_toko ?? 'Toko' }}</p>
            <p style="font-size:12px;color:#555;">{{ $pesanan->store?->alamat ?? '' }}</p>
        </div>
        <div class="meta">
            <p><strong>INVOICE</strong></p>
            <p>{{ $pesanan->nomor_order ?? ('#'.$pesanan->order_id) }}</p>
            <p>{{ $pesanan->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
            <p>Status: <strong>{{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}</strong></p>
        </div>
    </div>
    <div class="grid">
        <div>
            <h4>Ditagihkan Kepada</h4>
            <p><strong>{{ $pesanan->checkout?->nama_penerima ?? $pesanan->checkout?->user?->nama_lengkap ?? '-' }}</strong></p>
            <p>{{ $pesanan->checkout?->nomor_telepon ?? '-' }}</p>
            <p>{{ $pesanan->checkout?->alamat ?? '-' }}</p>
        </div>
        <div>
            <h4>Pembayaran & Pengiriman</h4>
            <p>{{ $pesanan->checkout?->payment?->paymentMethod?->nama_metode ?? 'Tunai' }} — {{ ucfirst($pesanan->checkout?->payment?->status ?? '-') }}</p>
            @php $ship = $pesanan->shipments->first(); @endphp
            <p>{{ $ship ? ($ship->courier?->nama_kurir ?? 'Kurir').' • Resi '.$ship->nomor_resi : ($pesanan->isAmbil() ? 'Ambil di toko' : 'Belum dikirim') }}</p>
            <p>{{ $pesanan->isOffline() ? 'Offline' : 'Online' }}@if($pesanan->catatan) • Catatan: {{ $pesanan->catatan }}@endif</p>
        </div>
    </div>
    <table>
        <thead>
            <tr><th>Produk</th><th class="r">Harga</th><th class="r">Qty</th><th class="r">Subtotal</th></tr>
        </thead>
        <tbody>
            @foreach ($pesanan->items as $it)
                <tr>
                    <td>{{ $it->nama_produk_snapshot }}</td>
                    <td class="r">Rp {{ number_format((float) ($it->harga_snapshot ?? 0), 0, ',', '.') }}</td>
                    <td class="r">{{ $it->quantity }}</td>
                    <td class="r">Rp {{ number_format((float) ($it->subtotal ?? 0), 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="total-box">
        <div><span>Subtotal</span><span>Rp {{ number_format((float) ($pesanan->subtotal ?? 0), 0, ',', '.') }}</span></div>
        <div><span>Pajak</span><span>Rp {{ number_format((float) ($pesanan->total_pajak ?? 0), 0, ',', '.') }}</span></div>
        <div><span>Layanan</span><span>Rp {{ number_format((float) ($pesanan->biaya_layanan ?? 0), 0, ',', '.') }}</span></div>
        <div><span>Ongkir</span><span>Rp {{ number_format((float) ($pesanan->total_ongkir ?? 0), 0, ',', '.') }}</span></div>
        <div class="grand"><span>Total</span><span>Rp {{ number_format((float) ($pesanan->grand_total ?? 0), 0, ',', '.') }}</span></div>
    </div>
    <p class="note">Invoice ini dicetak dari sistem Raliva Fashion pada {{ now()->translatedFormat('d M Y H:i') }}.</p>
    <div class="sign">
        <div><p>Pelanggan</p><br><br><p>( ........................ )</p></div>
        <div><p>{{ $pesanan->store?->nama_toko ?? 'Toko' }}</p><br><br><p>( ........................ )</p></div>
    </div>
</div>
</body>
</html>
