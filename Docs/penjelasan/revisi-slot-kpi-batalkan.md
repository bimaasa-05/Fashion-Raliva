# Penjelasan: Slot Flow, Batalkan, Alihkan, KPI

Untuk: Admin, Owner, SuperAdmin (nonteknis).

## 1. Alur slot: Beli → Bayar → SuperAdmin → Selesai

Alurnya sudah berjalan (bukti + pengajuan dalam 1 form → SuperAdmin Setujui/
Tolak → kuota grant). Yang dirapikan: endpoint verifikasi pembayaran yang mati
(tak ada pemanggil UI) dihapus — persetujuan lewat Setujui/Tolak saja.
Paket slot instan (Admin juga bisa) tetap seperti semula.

## 2. Tombol Batalkan dihapus total

Pesanan yang sudah dibayar tidak bisa dibatalkan dari Data Pesanan (tombol,
modal, route, dan method dihapus). Pembatalan hanya lewat **Tolak pembayaran**:
order kembali menunggu (pending) untuk upload ulang, lalu hangus otomatis bila
melewati batas waktu. Order tunai terverifikasi tidak tersentuh.

## 3. Alihkan hanya di Siap Kirim, boleh bolak-balik

Tombol Alihkan (dan modalnya) hanya muncul saat status **Siap Kirim**
(tanpa pengiriman aktif). Di dalam Siap Kirim boleh alih berkali-kali
(diantar ↔ ambil); aturan ongkir void tetap. Di status lain dan Selesai
tidak ada tombol. Backend mengunci aturan yang sama.

## 4. KPI baru

- **Owner**: kartu **ROI Toko** di dashboard + halaman keuangan
  (laba bersih / investasi).
- **Admin**: **Closing Rate** kini = pesanan selesai / seluruh order yang
  ditangani (definisi penjualan, bukan verifikasi); AOV + Rating tetap;
  kolom baru **LTV** = pendapatan / customer unik yang ditangani —
  tampil di tabel, mobile, PDF, dan Excel Rekap Karyawan.
- **Produksi**: tiga metrik sudah ada dan tetap (rata unit, rata durasi,
  persentase berhasil).

## Verifikasi

- Suite penuh: **144 passed + 2 skipped + 1 risky** (skip = DB 1 toko;
  risky = test komplain lama; bukan regresi).
- Test baru: slot approve/tolak/idempoten; tolak-bayar pending; alih
  non-siap-kirim; bound LTV + CR.
- `php -l`, `view:cache/clear`, `git diff --check` bersih.
- Catatan penting: 3 migrasi remote yang tertunda (`kota`, tarif, logo)
  dijalankan — sebelumnya bikin checkout 500.
- Sisa: verifikasi browser manual.
