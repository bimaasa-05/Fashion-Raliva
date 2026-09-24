# Revisi Rekap Karyawan dan KPI per Role

Tanggal keputusan: 2026-09-24.
Status dokumen: rencana disepakati, belum dieksekusi.

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

- [ ] Ganti pemetaan ID hardcoded dengan nama role resmi.
- [ ] Gunakan resolusi role yang sama dengan manajemen karyawan Owner.
- [ ] Tambahkan filter periode eksplisit dengan default `semua`.
- [ ] Pertahankan filter query pada export Excel dan PDF.
- [ ] Pisahkan metode service untuk Admin, Produksi, dan Gudang.
- [ ] Hindari query N+1 pada agregasi karyawan.

## Paket 2 — Kartu keuangan Owner

Tampilkan penjelasan berikut di kartu Owner:

```text
ROI = laba bersih / total investasi x 100%
LTV = total pendapatan / jumlah customer unik dalam periode
```

- [ ] Definisikan laba bersih dari pendapatan selesai dikurangi refund, expense, dan biaya iklan.
- [ ] Definisikan basis investasi awal dari kategori Modal, Investor, dan biaya iklan.
- [ ] Tampilkan periode dan definisi investasi pada kartu agar tidak disalahartikan.
- [ ] Jangan menampilkan ROI bila basis investasi nol; tampilkan status “belum dapat dihitung”.

## Paket 3 — Tabel Admin

Gunakan rumus berikut:

```text
Closing Rate = pembayaran sukses yang ditangani / seluruh pembayaran yang ditangani x 100%
AOV = pendapatan sukses yang diatribusikan / jumlah pesanan sukses yang diatribusikan
Rating = rata-rata rating review yang terhubung ke pembayaran yang diverifikasi karyawan
```

- [ ] Atribusikan pembayaran sukses ke `verifier_id`.
- [ ] Hitung pembayaran ditangani dari verifikasi diterima dan ditolak.
- [ ] Gunakan hanya order selesai untuk pendapatan dan AOV.
- [ ] Telusuri review melalui order item dan verifikasi pembayaran.
- [ ] Tampilkan rata-rata rating toko sebagai konteks, bukan sebagai skor pribadi.
- [ ] Beri label bahwa rating Admin adalah proxy atribusi, bukan moderator review.

## Paket 4 — Tabel Produksi

Gunakan rumus berikut:

```text
Rata-rata unit diminta = total jumlah diminta / total production order yang ditugaskan
Rata-rata output layak = total lulus QC / total production order yang selesai QC
Rata-rata durasi = total durasi order selesai / jumlah order selesai berdurasi valid
Persentase berhasil = production order selesai / seluruh production order yang ditugaskan x 100%
```

- [ ] Atribusikan production order melalui `assigned_to`.
- [ ] Pisahkan order tanpa penugasan ke baris “tanpa penugasan”.
- [ ] Kecualikan durasi yang tanggal mulai atau selesainya kosong.
- [ ] Gunakan status selesai resmi sebagai definisi berhasil.
- [ ] Tampilkan jumlah sampel untuk setiap rata-rata.
- [ ] Jangan menggabungkan unit diminta dan unit lulus QC dalam satu angka.

## Paket 5 — Tabel Gudang

Gunakan metrik yang tersedia berikut:

- [ ] Transfer yang diminta oleh karyawan.
- [ ] Transfer selesai dan dibatalkan.
- [ ] Rata-rata waktu dari diminta sampai diterima.
- [ ] Mutasi stok yang dibuat karyawan.
- [ ] Opname yang dibuat karyawan.
- [ ] Akurasi opname berdasarkan selisih nol.
- [ ] Kerusakan yang dicatat karyawan.
- [ ] Atribusikan transfer melalui peminta, bukan penyetuju.
- [ ] Kecualikan transfer tanpa tanggal valid dari rata-rata durasi.
- [ ] Beri label periode dan jumlah sampel pada setiap metrik.

## Paket 6 — Tampilan dan export

- [ ] Render tabel berbeda dengan kondisi filter role aktif.
- [ ] Buat header dan baris khusus untuk Admin, Produksi, dan Gudang.
- [ ] Sesuaikan total dan rata-rata dengan jenis metrik tiap tabel.
- [ ] Jangan menjumlahkan persen atau rating secara langsung.
- [ ] Gunakan rata-rata tertimbang untuk Closing Rate, AOV, rating, dan keberhasilan.
- [ ] Buat skema Excel berbeda untuk tiap role.
- [ ] Buat tampilan PDF berbeda untuk tiap role.
- [ ] Perbarui lebar kolom dan format Rupiah sesuai skema aktif.
- [ ] Tambahkan catatan definisi metrik pada halaman dan dokumen export.

## Verifikasi

- [ ] `php -l` untuk controller dan service yang diubah.
- [ ] `php artisan view:cache` lalu `view:clear`.
- [ ] Manual:
  - [ ] Filter Admin menampilkan kolom CR, AOV, dan rating.
  - [ ] Filter Produksi menampilkan unit, durasi, dan keberhasilan.
  - [ ] Filter Gudang menampilkan transfer dan akurasi stok.
  - [ ] Filter semua mempertahankan ringkasan keuangan lama.
  - [ ] Export mengikuti filter role dan periode.
- [ ] Tambahkan test agregasi untuk tiap role dan kondisi data kosong.

## Risiko

- Verifier pembayaran belum tentu karyawan yang melayani customer secara langsung.
- Review yang terhubung lewat order item tetap merupakan proxy, bukan bukti penanganan.
- Rata-rata durasi sensitif terhadap tanggal yang kosong atau tidak valid.
- Skema export yang bercabang menambah biaya pemeliharaan PDF dan Excel.
