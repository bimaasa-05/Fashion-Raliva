# Revisi Produksi — QC, Gudang & Tampilan

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati, dieksekusi bertahap.

Scope: role Produksi (data-produksi, pemeriksaan-kualitas, produk-selesai, riwayat, detail timeline) + notifikasi Admin/Gudang se-toko. Customer tanpa perubahan.

## Keputusan yang dikunci

- Urutan Data Produksi & QC sudah terbaru → terlama: **tanpa perubahan kode** (terverifikasi).
- Produksi **tidak input Gagal** — `jumlah_gagal = total_qty_order − jumlah_berhasil` otomatis; `berhasil > total` ditolak.
- QC: hanya input `jumlah_lulus`; `jumlah_gagal` otomatis (`total − lulus`).
- Shortfall (`lulus < total`) → tercatat `kekurangan_gudang`, notifikasi **Gudang se-toko + Admin se-toko**. **Tanpa write stok** (dipotong normal oleh `StockDeductionService` saat diterima — auto-potong di QC = double deduction).
- QC dua tombol: **Selesai QC + Packing** (lama → `siap_kirim`) dan **Gagal — Hubungi Admin** (tetap `menunggu_qc` + penanda + catatan wajib, notifikasi **Admin se-toko saja**, bukan global).
- Bold teks jadwal/deadline di tabel produksi (desktop + mobile).
- Catatan customer (`Order.catatan` + `catatan_custom` item) tampil di baris Data Produksi, daftar QC, modal timeline detail.
- Filter Siap Kirim di Data Produksi (query ikut ambil `siap_kirim`, baris read-only).

## Paket 1 — Selesai Produksi otomatis

- [x] `updateStatus`: hapus `jumlah_gagal` dari validasi; hitung otomatis; tolak `berhasil > total_qty`.
- [x] Modal Selesai Produksi: hapus input Gagal + tampilkan rumus otomatis ("Gagal dihitung otomatis: total − berhasil").
- [x] Test: gagal otomatis; berhasil berlebih ditolak.

## Paket 2 — QC shortfall Gudang

- [x] Migrasi: `orders.kekurangan_gudang` (int default 0), `orders.qc_perlu_admin_pada` (nullable datetime), `orders.qc_perlu_admin_catatan` (nullable).
- [x] `NotificationService::sendToRoleInStores()` (scope toko via `storeAssignments` aktif).
- [x] QC `store`: hanya `jumlah_lulus`; gagal otomatis; shortfall > 0 → isi `kekurangan_gudang` + notifikasi Gudang se-toko + Admin se-toko; tampilkan angka di hasil QC/produk-selesai/riwayat/detail.
- [x] Test: shortfall tercatat + notifikasi; tanpa shortfall nihil.

## Paket 3 — QC dua tombol

- [x] Route + `tandaiGagal`: catatan wajib min 10; tetap `menunggu_qc`; isi penanda; notifikasi Admin se-toko; badge "Menunggu Admin" di daftar QC.
- [x] Modal QC: dua tombol submit (Selesai QC + Packing | Gagal — Hubungi Admin).
- [x] Test: gagal → status tetap + penanda + notifikasi; tanpa catatan ditolak.

## Paket 4 — Tampilan

- [x] Bold jadwal/deadline + countdown (desktop + mobile).
- [x] Catatan customer di baris Data Produksi, daftar QC, modal detail timeline.
- [x] Query Data Produksi + `siap_kirim` (read-only) + opsi filter Siap Kirim (JS).
- [x] Test render.

## Verifikasi

- [x] `php -l`, `view:cache` → `view:clear`, `git diff --check`.
- [x] Test baru + regresi terkait.
- [ ] Manual: Selesai Produksi tanpa Gagal; QC shortfall; QC gagal; filter siap kirim; mobile.
- [ ] Penjelasan: `Docs/penjelasan/revisi-produksi-qc-gudang.md`.
