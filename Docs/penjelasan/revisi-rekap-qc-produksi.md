# Revisi Rekap Karyawan, Produksi Connect, AOV & QC — Penjelasan

Tanggal: 30 September 2026.

## 1. Investasi: label tetap, keterangan jujur

Kolom Investasi memang berisi modal pribadi Owner/Admin plus biaya iklan — bukan dana
investor luar. Labelnya dipertahankan, tapi keterangannya kini menjelaskannya, dan bug
total ganda (angka toko terjumlah berkali-kali saat pemilik lebih dari satu) diperbaiki.

## 2. Rekap produksi kini connect

Sebelumnya metrik produksi selalu kosong karena sistem membaca tabel lama yang tidak
lagi dipakai. Kini setiap kali Produksi menekan Accept, namanya tercatat di pesanan,
sehingga Rekap Karyawan langsung menampilkan: jumlah ditugaskan, rata-rata unit,
rata-rata durasi, tingkat keberhasilan, dan rata-rata hasil QC. Data lama sebelum
perubahan ini tidak bisa dimasukkan (nama pelaksana waktu itu tidak tercatat).

## 3. Verifikasi & AOV

Pembayaran via saldo oleh customer kini tetap dihitung sebagai kinerja Admin toko
(sebelumnya hilang karena tercatat atas nama customer). Di ringkasan Owner ada kartu
baru AOV Toko yang mencakup pesanan pending, dibayar, dan selesai.

## 4. Produksi & QC lebih transparan

- Modal Selesai Produksi menampilkan total pcs di atas kolom isian, ditebalkan.
- Modal Tanggapi QC Admin menampilkan angka Total/Berhasil/Gagal sebelum memutuskan.
- Detail produksi Admin menampilkan badge Status Produksi + Status QC, hasil produksi
  meski QC belum diinput, penanda QC-gagal, dan catatan Admin.
- Catatan Admin saat menanggapi QC kini tersimpan dan bisa dibaca tim Produksi.

## 5. Role lain

Penyetuju pemindahan stok Gudang kini ikut tercatat (sebelumnya hanya peminta).
Tidak ada role Kurir di sistem — pengiriman dikerjakan Admin — sehingga tidak ada
rekap kurir yang perlu diperbaiki.

## Verifikasi

11 test baru lulus (rekap 2, QC 5, produksi 2, verifier 1, gudang 1) plus regresi
`AdminQcTanggapanTest` tetap hijau (1 skip bawaan). Migrasi: `qc_admin_catatan`,
`produksi_oleh`. Disarankan cek manual: accept 1 produksi lalu lihat rekap,
tanggapi 1 QC gagal lalu cek catatan di sisi Produksi.
