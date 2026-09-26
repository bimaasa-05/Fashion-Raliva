# Revisi — Slot Flow, Batalkan, Alihkan, KPI

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati, dieksekusi.

## Keputusan yang dikunci

- Slot: alur Buy+Pay 1 POST → SA setujui/tolak → grant sudah fungsional;
  rapikan endpoint verifikasi mati; paket Admin ikut pola sama.
- Batalkan: hapus total (tombol + modal + route + method); cancel hanya via
  tolak pembayaran (order pending → expire otomatis).
- Alihkan: hanya di Siap Kirim, boleh bolak-balik (tanpa migrasi/penanda).
- KPI: ROI tampil (Owner), CR penjualan = selesai/total order (Admin),
  LTV per Admin masuk Rekap; Produksi tiga metrik tetap.

## Paket 1 — Slot flow

- [x] Endpoint `verifikasiPembayaran` mati: hapus (tidak ada pemanggil UI).
- [x] Test: request → approve grant; tolak tanpa grant; idempoten.

## Paket 2 — Hapus Batalkan

- [x] Hapus tombol + modal (desktop + mobile), route, `batalkan()`.
- [x] Tulis ulang 2 test cancel (tolak bayar → pending).

## Paket 3 — Alihkan

- [x] Render hanya saat `siap_kirim`; bolak-balik; ongkir void tetap.
- [x] Test render + bolak-balik.

## Paket 4 — KPI

- [x] Kartu ROI (dashboard + keuangan Owner) dari `ringkasanKeuangan`.
- [x] CR penjualan + kolom LTV Admin (tabel + excel + pdf + total + footnote).
- [x] Test bound LTV + CR baru.

## Paket 5 — Verifikasi

- [x] `php -l`, `view:cache/clear`, `git diff --check`, regresi, penjelasan
      `Docs/penjelasan/revisi-slot-kpi-batalkan.md`, manual.
