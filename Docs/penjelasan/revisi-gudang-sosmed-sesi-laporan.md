# Revisi Gudang, Sosmed, Sesi, dan Laporan — Penjelasan

Tanggal: 29 September 2026.

## 1. Gudang bisa dikelola Owner

Halaman Gudang Owner yang tadinya hanya daftar kini punya tombol **Tambah Gudang**,
tombol **Ubah** di tiap kartu, tombol **Aktif/Nonaktif**, dan tombol **Petugas** untuk
menugaskan karyawan Gudang ke lokasi tertentu. Gudang terakhir yang aktif tidak bisa
dimatikan agar operasional tidak berhenti total. Semua aksi tercatat di riwayat aktivitas.

## 2. Media sosial toko

Super Admin mendaftarkan platform (TikTok, Instagram, YouTube, dan seterusnya) di
Pengaturan Sistem, lengkap dengan tombol aktif/nonaktif. Owner lalu mengisi tautan
sosmed tokonya di Data Toko: pilih dari daftar, atau tulis nama sendiri bila platformnya
belum ada (misalnya Threads). Tautan tampil di halaman Tentang toko untuk customer.
Kolom Instagram contoh yang tidak tersimpan sudah dihapus.

## 3. Form tidak lagi "mati" setelah lama dibuka

Keluhan form error setelah dibiarkan beberapa jam disebabkan sesi kedaluwarsa
(batas idle 2 jam). Sekarang bila itu terjadi, pengguna melihat halaman penjelasan
berbahasa Indonesia dengan tombol **Masuk Kembali**, dan setelah login muncul pemberitahuan
"Sesi Anda telah berakhir". Permintaan yang berjalan di latar juga otomatis diarahkan
ke login, bukan menggantung.

## 4. Laporan Admin sinkron dengan dashboard

Ada dua perbaikan. Pertama, definisi pendapatan disamakan: pesanan berstatus refund
tidak lagi dihitung sebagai pendapatan, selaras dengan grafik omzet dashboard.
Kedua, bila Admin belum ditugaskan ke toko mana pun, halaman menjelaskan sebabnya
("minta Owner menugaskan lewat menu Karyawan") alih-alih menampilkan angka nol misterius.

Tampilan halaman juga ditata ulang dari atas ke bawah: 4 kartu ringkasan, lalu
Grafik Tren Omzet (kini setengah lebar) berdampingan dengan Metode Pembayaran
Terbanyak — lengkap dengan nama metode teratas, jumlah transaksi, total rupiah,
dan diagram donat distribusinya. Di bawah grafik ada tabel Pesanan Selesai
(nomor, tanggal, customer, metode bayar, total, status) yang ikut filter tanggal,
diikuti rincian Pendapatan per Metode Pembayaran seperti sebelumnya.

## Verifikasi

13 test baru lulus (Gudang 4, Sosmed 4, Sesi 3, Laporan 2), test login terkait tetap
hijau, dan pemeriksaan kode (`php -l`, `view:cache/clear`, `git diff --check`) bersih.
Disarankan cek manual: tambah gudang, isi sosmed dan lihat halaman Tentang, biarkan sesi
kedaluwarsa lalu submit form, serta buka laporan sebagai Admin tanpa toko.
