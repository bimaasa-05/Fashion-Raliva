# Revisi — Tanggapan Admin atas QC Gagal ("Menunggu Admin")

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati (Rework + Lanjut, pengingat berkala), dieksekusi.

Scope: role Admin (pesanan, dashboard) + scheduler + notifikasi toko. Produksi/Gudang tanpa perubahan besar (hanya terima notifikasi).

## Keputusan yang dikunci

- Tidak ada tombol **Batalkan** untuk order bertanda QC Gagal (tetap ikut aturan pembayaran).
- **Rework Produksi**: hapus penanda, status → `menunggu_produksi`, notifikasi Produksi se-toko + catatan Admin.
- **Lanjut QC**: hapus penanda, status tetap `menunggu_qc`, notifikasi Produksi se-toko (QC ulang).
- Diam > 24 jam → **pengingat ulang** ke Admin se-toko (setelah tanggapan, pengingat berhenti); tidak auto-naik ke Owner.

## Paket 1 — Visibilitas

- [x] Badge **"QC Gagal"** di baris pesanan Admin (desktop + mobile) + keterangan Produksi (tooltip/teks).
- [x] Filter status Admin tambah **"Menunggu QC"** (query ikut `menunggu_qc`).
- [x] Notifikasi QC Gagal deep-link ke `admin.pesanan` + `?filter=` (order tersebut mudah ditemukan).

## Paket 2 — Aksi Admin

- [x] Route `POST admin/pesanan/{pesanan}/qc-tanggapan` (aksi `rework` | `lanjut`).
- [x] Validasi: penanda ada + status `menunggu_qc` + scope toko Admin; selain itu tolak.
- [x] `rework`: hapus penanda, status `menunggu_produksi`, log, notifikasi Produksi se-toko ("QC gagal — produksi ulang", sertai catatan Admin opsional).
- [x] `lanjut`: hapus penanda, status tetap `menunggu_qc`, log, notifikasi Produksi se-toko ("QC ulang").
- [x] Tombol hanya tampil untuk order bertanda.
- [x] Test: rework/lanjut berfungsi + tolak tanpa penanda + tolak scope toko + notifikasi terkirim.

## Paket 3 — Pengingat anti-diam

- [x] Scheduler harian: order bertanda > 24 jam → notifikasi ulang Admin se-toko (`qc:remind`), berhenti bila sudah ditanggapi.
- [x] Kartu **"QC Menunggu Tanggapan: N"** di dashboard Admin → link filter Menunggu QC.
- [x] Test dengan travel time 24 jam: diingatkan; setelah ditanggapi tidak diingatkan lagi.

## Paket 4 — Verifikasi

- [x] `php -l`, `view:cache` → `view:clear`, `git diff --check`.
- [x] Test baru + regresi (ProduksiQcGudang, Admin pesanan).
- [x] Prasyarat scheduler (`schedule:run`) dicatat di penjelasan.
- [ ] Manual: badge, filter, rework/lanjut, dashboard kartu.
- [x] Penjelasan: `Docs/penjelasan/revisi-qc-gagal-tanggapan-admin.md`.
