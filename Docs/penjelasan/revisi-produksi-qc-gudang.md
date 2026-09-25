# Penjelasan: Revisi Produksi — QC, Gudang & Tampilan

Untuk: Admin, Owner, dan tim Produksi (nonteknis).

## Ringkasan

Lima perubahan di halaman Produksi:

1. **Tidak perlu input Gagal** — jumlah gagal dihitung otomatis.
2. **Kekurangan otomatis dari Gudang** — bila hasil tidak sesuai pesanan, sisa diambil dari stok gudang + Gudang dinotifikasi.
3. **QC punya dua tombol** — Selesai atau Gagal (hubungi Admin).
4. **Jadwal dicetak tebal** — waktu produksi lebih mudah dibaca.
5. **Catatan customer tampil** + **filter Siap Kirim** di Data Produksi.

## 1. Selesai Produksi tanpa input Gagal

Di modal "Selesai Produksi" kolom **Jumlah Gagal dihapus**. Cukup isi **Jumlah Berhasil** — sistem menghitung sendiri:

> Gagal = Total pesanan − Berhasil

Contoh: customer pesan 5, yang jadi 4 → gagal otomatis 1. Bila isi berhasil **melebihi** total pesanan, sistem menolak.

## 2. Kekurangan diambil dari Gudang (otomatis tercatat)

Di QC juga tidak ada input Gagal — cukup isi **Jumlah Lulus**. Bila lulus kurang dari total pesanan:

- Sistem mencatat **"Kekurangan N pcs dari Gudang"** pada pesanan tersebut.
- **Staff Gudang se-toko + Admin se-toko** otomatis menerima notifikasi untuk menyiapkan kekurangan dari stok gudang.
- Angka kekurangan tampil di: hasil QC, Produk Selesai ("+N dari Gudang"), Riwayat, dan detail timeline.

Catatan: stok **tidak** dipotong otomatis saat ini — pemotongan tetap berjalan normal saat pesanan diterima customer (bila dipotong dua kali, stok jadi minus dua kali).

## 3. QC: dua tombol

| Tombol | Efek |
|---|---|
| **Selesai QC + Packing** | Perilaku lama: pesanan lanjut ke Siap Kirim. |
| **Gagal — Hubungi Admin** | Pesanan **tetap** Menunggu QC + ditandai **"Menunggu Admin"** + **Admin se-toko** (bukan global) menerima notifikasi berisi keterangan. Wajib isi keterangan minimal 10 karakter. |

## 4. Tampilan

- **Jadwal & sisa waktu** di tabel Data Produksi sekarang **tebal** (desktop + HP).
- **Catatan customer** (pesan yang ditulis customer saat order) tampil di: baris Data Produksi, daftar QC, dan modal detail timeline.
- **Filter "Siap Kirim"** ditambahkan di Data Produksi — barisnya hanya-baca (aksi: lihat timeline).

## Sisi teknis (Admin IT)

- Migrasi `2026_09_25_000001`: kolom `orders.kekurangan_gudang`, `qc_perlu_admin_pada`, `qc_perlu_admin_catatan`.
- `NotificationService::sendToRoleInStores()` — kirim notifikasi per role **scope toko** (dipakai untuk Gudang + Admin se-toko).
- Route baru: `POST produksi/pemeriksaan-kualitas/{order}/gagal` → `tandaiGagal`.
- Tombol Gagal memakai `formaction` (satu form, dua tujuan).
- Test: `tests/Feature/ProduksiQcGudangTest.php` — 8 test (gagal otomatis, tolak berlebih, shortfall + notifikasi, tanpa shortfall nihil, QC gagal + scope admin, catatan wajib, render filter + catatan, render dua tombol + badge).
- Verifikasi: `php -l`, `view:cache/clear`, 8/8 test + regresi 22/22 lulus, `git diff --check` bersih.
- Catatan test: ganti user lintas area (Admin → Produksi) wajib `flushSession` dulu (`actingAsFresh`), kalau tidak auth hilang dan request mental ke login.

## Belum dikerjakan

- Verifikasi browser manual (checklist di plan).
- Tindak lanjut Admin atas penanda "Menunggu Admin" (alurnya manual untuk sekarang).
