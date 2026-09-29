# Plan: Rekap Karyawan, Produksi Connect, AOV & QC Visibility

Tanggal: 30 September 2026. Status: SELESAI DIEKSEKUSI 30 Sep 2026.

## Temuan kunci (sudah diverifikasi ke kode)

- Metrik produksi di `KaryawanReportService` membaca tabel legacy `production_orders`
  (`assigned_to` tidak pernah ditulis controller manapun — grep membuktikan).
  Alur live (accept/selesai/QC) memakai tabel `orders`. Ini sebab rekap produksi kosong.
- Verifikasi pembayaran Admin SUDAH connect ke rekap owner via
  `payment_verifications.verifier_id`. Celah: auto-verify self-checkout customer
  teratribusi ke customer itu sendiri.
- Catatan Admin saat tanggapi QC hanya ephemeral (log + notif), tanpa kolom DB.

## Paket 1 — Investasi (label tetap, keterangan diperbaiki)

- Label "Investasi" DIPERTAHANKAN (keputusan user 30 Sep).
- Tulis ulang footnote di `Owner/rekap-karyawan/index.blade.php` + `pdf.blade.php`:
  "Investasi = modal pribadi Owner/Admin (kategori Modal/Investor) + biaya iklan.
  Bukan dana investor luar."
- Perbaiki `RekapKaryawanController::hitungTotal()` (baris ±228) yang men-sum
  `investasi` antar-baris owner (double-count bila >1 owner) — pakai nilai toko sekali.
- Test: footnote tampil; total investasi tidak double.

## Paket 2 — Rekap produksi connect (butuh 1 migrasi)

- Migrasi: `orders.produksi_oleh` nullable FK `users` — diisi saat accept
  (`Produksi/DataProduksiController@accept`).
- Tulis ulang metrik produksi di `KaryawanReportService` agar baca tabel `orders`:
  - ditugaskan = order `produksi_oleh=user` + `produksi_dimulai_pada` not null
  - selesai = `produksi_selesai_pada` not null; sukses% = selesai/ditugaskan
  - rata unit diminta = SUM `order_items.quantity` / ditugaskan
  - rata output layak = SUM `quality_checks.jumlah_lulus` (join via `order_id`,
    `checked_by=user`) / distinct order
  - rata durasi = AVG (`selesai_pada - dimulai_pada`), filter periode pakai
    `produksi_dimulai_pada`
- Catatan: data lama (sebelum migrasi) tak bisa diatribusikan — hitung mulai ke depan.
- Test: accept → masuk rekap; selesai → durasi/unit/berhasil terisi.

## Paket 3 — Atribusi verifikasi self-checkout

- `Customer/CheckoutController` auto-verify: bila aktor = customer sendiri,
  fallback `verifier_id` ke Admin aktif pertama di toko tsb (masuk rekap owner).
  Audit asli tetap via `ActivityLog`.
- Test: rekap admin toko bertambah setelah self-checkout lunas.

## Paket 4 — AOV Owner (pending + dibayar + selesai)

- Metrik baru level toko di `metrikOwner()`: `aovOwner = SUM(grand_total) /
  COUNT(order)` untuk status `pending_payment + dibayar + selesai` (keputusan user).
- Tampil di ringkasan Owner (view + pdf + export). AOV per-admin tidak diubah.
- Test: order pending ikut penyebut/pembilang AOV owner.

## Paket 5 — Qty di atas tombol Selesai produksi

- `Produksi/data-produksi/index.blade.php` modal selesai: pindahkan
  "Total pesanan: X pcs" ke ATAS input `jumlah_berhasil`, ditebalkan.
- Test: assert urutan teks di atas input.

## Paket 6 — Visibilitas QC (butuh 1 migrasi kecil)

- Migrasi: `orders.qc_admin_catatan` nullable (500) — disimpan di `qcTanggapan()`,
  ditampilkan di sisi Produksi (tabel/modal `title`) + detail Admin.
- Modal Tanggapi QC Admin: box angka "Total X pcs · Berhasil Y · Gagal Z"
  (dari items + `orders.jumlah_*`) di atas box keterangan.
- Partial `modal-produksi-detail`: fallback `orders.jumlah_berhasil/gagal` bila
  belum ada row `quality_checks`; baris "Penanda QC Gagal" bila ada;
  badge Status Produksi + Status QC terpisah (ganti status mentah).
- `modal-detail` Admin: baris read-only Hasil Produksi/QC.
- Tabel Produksi: sel hasil jadi `berhasil/total pcs · gagal`, `title` = catatan.
- Test per titik tampil.

## Paket 7 — Audit role lain

- Gudang: hitung `approved_by` pemindahan stok (kolom ada, service mengabaikan).
- Kurir: `shipments` tanpa FK aktor — saat eksekusi, telusuri siapa memproses
  kirim lalu tambah kolom aktor + metrik minimal; bila alur tak jelas, pecah jadi
  plan lanjutan.
- Test konektivitas per role.

## Verifikasi (saat eksekusi)

Migrasi fresh-check → `php -l` → `view:cache/clear` → `git diff --check` →
test baru + regresi (rekap, produksi, QC, checkout) → update doc penjelasan.

Estimasi: ±12 file ubah, 2 migrasi, ±7 test baru. Urutan: 1 → 5 → 6 → 2 → 3 → 4 → 7.
