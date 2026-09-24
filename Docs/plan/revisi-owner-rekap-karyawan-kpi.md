# Revisi Rekap Karyawan dan KPI per Role

Tanggal keputusan: 2026-09-24.
Status dokumen: Paket 1–6 selesai di kode dan test otomatis 2026-09-24; verifikasi browser manual belum dilakukan.

Scope: file Owner untuk rekap karyawan, service laporan, tampilan, dan export.
Query boleh membaca data operasional Produksi, Gudang, dan Admin, tetapi workflow
role tersebut tidak diubah.

## Keputusan yang dikunci

- Tabel rekap berbeda menurut filter Admin, Produksi, dan Gudang.
- Gunakan data yang sudah tersedia, tanpa relasi penugasan manual baru.
- Semua metrik atribusi harus diberi label sebagai proxy bila relasi langsung tidak ada.
- Tabel “semua role” yang lama dipertahankan sebagai ringkasan keuangan.
- Export Excel dan PDF mengikuti kolom tabel role yang aktif.

## Fakta saat ini

- Role dipetakan dari ID hardcoded pada `RekapKaryawanController:106-110`.
- Service hanya menghitung pesanan, pendapatan, refund, expense, pengeluaran, dan bersih.
- Review tidak menyimpan karyawan penangan.
- Review dapat ditelusuri lewat order item, checkout, pembayaran, dan verifier pembayaran.
- Production order mempunyai `assigned_to`, waktu mulai/selesai, item, hasil, dan QC.
- Transfer stok mempunyai peminta, status, dan waktu diminta/diterima.
- Mutasi, opname, dan kerusakan stok mempunyai pembuat.
- Export masih memakai satu skema kolom umum.

## Paket 1 — Fondasi role dan periode

- [x] Ganti pemetaan ID hardcoded dengan nama role resmi.
- [x] Gunakan resolusi role yang sama dengan manajemen karyawan Owner.
- [x] Tambahkan filter periode eksplisit dengan default `semua`.
- [x] Pertahankan filter query pada export Excel dan PDF.
- [x] Pisahkan metode service untuk Admin, Produksi, dan Gudang.
- [x] Hindari query N+1 pada agregasi karyawan.

## Paket 2 — Kartu keuangan Owner

Tampilkan penjelasan berikut di kartu Owner:

```text
ROI = laba bersih / total investasi x 100%
LTV = total pendapatan / jumlah customer unik dalam periode
```

- [x] Definisikan laba bersih dari pendapatan selesai dikurangi refund, expense, dan biaya iklan.
- [x] Definisikan basis investasi awal dari kategori Modal, Investor, dan biaya iklan.
- [x] Tampilkan periode dan definisi investasi pada kartu agar tidak disalahartikan.
- [x] Jangan menampilkan ROI bila basis investasi nol; tampilkan status “belum dapat dihitung”.

## Paket 3 — Tabel Admin

Gunakan rumus berikut:

```text
Closing Rate = pembayaran sukses yang ditangani / seluruh pembayaran yang ditangani x 100%
AOV = pendapatan sukses yang diatribusikan / jumlah pesanan sukses yang diatribusikan
Rating = rata-rata rating review yang terhubung ke pembayaran yang diverifikasi karyawan
```

- [x] Atribusikan pembayaran sukses ke `verifier_id`.
- [x] Hitung pembayaran ditangani dari verifikasi diterima dan ditolak.
- [x] Gunakan hanya order selesai untuk pendapatan dan AOV.
- [x] Telusuri review melalui order item dan verifikasi pembayaran.
- [x] Tampilkan rata-rata rating toko sebagai konteks, bukan sebagai skor pribadi.
- [x] Beri label bahwa rating Admin adalah proxy atribusi, bukan moderator review.

## Paket 4 — Tabel Produksi

Gunakan rumus berikut:

```text
Rata-rata unit diminta = total jumlah diminta / total production order yang ditugaskan
Rata-rata output layak = total lulus QC / total production order yang selesai QC
Rata-rata durasi = total durasi order selesai / jumlah order selesai berdurasi valid
Persentase berhasil = production order selesai / seluruh production order yang ditugaskan x 100%
```

- [x] Atribusikan production order melalui `assigned_to`.
- [x] Pisahkan order tanpa penugasan ke baris “tanpa penugasan”.
- [x] Kecualikan durasi yang tanggal mulai atau selesainya kosong.
- [x] Gunakan status selesai resmi sebagai definisi berhasil.
- [x] Tampilkan jumlah sampel untuk setiap rata-rata.
- [x] Jangan menggabungkan unit diminta dan unit lulus QC dalam satu angka.

## Paket 5 — Tabel Gudang

Gunakan metrik yang tersedia berikut:

- [x] Transfer yang diminta oleh karyawan.
- [x] Transfer selesai dan dibatalkan.
- [x] Rata-rata waktu dari diminta sampai diterima.
- [x] Mutasi stok yang dibuat karyawan.
- [x] Opname yang dibuat karyawan.
- [x] Akurasi opname berdasarkan selisih nol.
- [x] Kerusakan yang dicatat karyawan.
- [x] Atribusikan transfer melalui peminta, bukan penyetuju.
- [x] Kecualikan transfer tanpa tanggal valid dari rata-rata durasi.
- [x] Beri label periode dan jumlah sampel pada setiap metrik.

## Paket 6 — Tampilan dan export

- [x] Render tabel berbeda dengan kondisi filter role aktif.
- [x] Buat header dan baris khusus untuk Admin, Produksi, dan Gudang.
- [x] Sesuaikan total dan rata-rata dengan jenis metrik tiap tabel.
- [x] Jangan menjumlahkan persen atau rating secara langsung.
- [x] Gunakan rata-rata tertimbang untuk Closing Rate, AOV, rating, dan keberhasilan.
- [x] Buat skema Excel berbeda untuk tiap role.
- [x] Buat tampilan PDF berbeda untuk tiap role.
- [x] Perbarui lebar kolom dan format Rupiah sesuai skema aktif.
- [x] Tambahkan catatan definisi metrik pada halaman dan dokumen export.

## Verifikasi

- [x] `php -l` untuk controller dan service yang diubah.
- [x] `php artisan view:cache` lalu `view:clear`.
- [ ] Manual:
  - [ ] Filter Admin menampilkan kolom CR, AOV, dan rating.
  - [ ] Filter Produksi menampilkan unit, durasi, dan keberhasilan.
  - [ ] Filter Gudang menampilkan transfer dan akurasi stok.
  - [ ] Filter semua mempertahankan ringkasan keuangan lama.
  - [ ] Export mengikuti filter role dan periode.
- [x] Test agregasi tiap role (`KaryawanKpiTest`: 9 test, 122 assertions) + render tiap filter role.
- [x] Bugfix 2026-09-24: baris role kini selalu membawa key keuangan dasar agar filter Semua tidak error `Undefined array key "pengeluaran"`.
- [x] Default filter role adalah Admin (bukan Semua); pilihan tidak valid jatuh kembali ke Admin.
- [x] Kategori Semua Role dihapus total (dropdown, tabel, export, PDF); hanya Admin/Produksi/Gudang.

## Risiko

- Verifier pembayaran belum tentu karyawan yang melayani customer secara langsung.
- Review yang terhubung lewat order item tetap merupakan proxy, bukan bukti penanganan.
- Rata-rata durasi sensitif terhadap tanggal yang kosong atau tidak valid.
- Skema export yang bercabang menambah biaya pemeliharaan PDF dan Excel.
