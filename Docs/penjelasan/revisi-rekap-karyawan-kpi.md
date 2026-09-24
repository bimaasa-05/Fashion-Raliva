# Penjelasan Revisi Rekap Karyawan dan KPI per Role

Tanggal selesai: 2026-09-24.

## Untuk siapa dokumen ini

Dokumen ini terutama untuk **Owner Toko** yang membaca Rekap Karyawan.

> Cara membaca cepat 30 detik:
> - Tabel selalu menampilkan satu role: Admin, Produksi, atau Gudang (default Admin).
> - Tidak ada lagi filter Semua Role; halaman selalu menampilkan satu tabel role (default Admin).
> - Kartu atas menampilkan ROI toko dan LTV pelanggan.
> - Semua metrik atribusi diberi label periode dan jumlah sampel bila relevan.

Istilah penting:

- **CR (Closing Rate)** = pembayaran sukses yang ditangani / seluruh pembayaran yang ditangani karyawan.
- **AOV (Average Order Value)** = total pendapatan sukses / jumlah pesanan sukses.
- **ROI** = laba bersih / total investasi × 100%.
- **LTV** = total pendapatan / jumlah customer unik dalam periode.
- **Proxy** = angka pendekatan karena sistem tidak mencatat relasi langsungnya.

---

## 1. Filter role dan periode

Filter role memakai nama role resmi (`Admin/Produksi/Gudang`), bukan lagi ID angka yang rapuh. Filter default adalah **Admin**; opsi Semua Role tetap tersedia.

Filter periode (`dari`/`sampai`, default `semua`) membatasi data berdasarkan tanggal catatan: tanggal verifikasi untuk pembayaran, tanggal dibuat untuk sisanya. Export Excel/PDF mengikuti filter role dan periode yang aktif.

---

## 2. Kartu keuangan Owner

- **ROI Toko**: laba bersih dibagi total investasi. Investasi = pemasukan berkategori Modal/Investor ditambah biaya iklan. Bila investasi nol, kartu menampilkan `—` (belum dapat dihitung), bukan 0%.
- **LTV Pelanggan**: rata-rata pendapatan per customer unik dari order selesai.
- Kartu keempat mengikuti filter: Total Bersih (semua), rata-rata CR (admin), Keberhasilan (produksi), Akurasi Opname (gudang).

---

## 3. Tabel Admin — CR, AOV, Rating

- **CR**: pembayaran berstatus diterima dibagi seluruh pembayaran yang ditangani (diterima + ditolak) oleh karyawan sebagai verifier.
- **AOV**: pendapatan order selesai yang pembayarannya diverifikasi karyawan tersebut, dibagi jumlah ordernya.
- **Rating**: rata-rata ulasan pada order yang pembayarannya diverifikasi karyawan tersebut. Ini **proxy**, bukan bukti pelayanan langsung — review tidak menyimpan karyawan penangan.
- Total baris memakai rata-rata tertimbang (tidak dijumlahkan langsung).

---

## 4. Tabel Produksi — unit, durasi, keberhasilan

Dihitung dari production order yang ditugaskan ke karyawan (`assigned_to`):

- **Ditugaskan / Selesai / Keberhasilan %** = selesai / ditugaskan.
- **Rata-rata unit diminta** = total unit diminta / total order ditugaskan.
- **Rata-rata output layak** = total lulus QC / jumlah order yang di-QC.
- **Rata-rata durasi** = hanya dari order selesai yang punya tanggal mulai dan selesai valid, lengkap dengan jumlah sampel.
- Order tanpa penugasan tidak masuk ke baris karyawan mana pun.

---

## 5. Tabel Gudang — transfer, mutasi, akurasi

- **Transfer**: diminta oleh karyawan (peminta, bukan penyetuju), beserta jumlah selesai dan batal.
- **Rata-rata putaran**: jam dari diminta sampai diterima, hanya transfer selesai bertanggal valid.
- **Mutasi / Opname / Kerusakan**: jumlah catatan yang dibuat karyawan.
- **Akurasi** = opname tanpa selisih / total opname.
- Total baris memakai rata-rata tertimbang untuk akurasi.

---

## 6. Batasan yang masih berlaku

- Verifier pembayaran belum tentu karyawan yang melayani customer secara langsung.
- Rating Admin tetap proxy, bukan bukti penanganan.
- Rata-rata durasi sensitif terhadap tanggal kosong atau tidak valid (baris tanpa tanggal dikecualikan dan sampelnya ditampilkan).
- Verifikasi browser manual masih menjadi pekerjaan lanjutan.

---

## Apendiks teknis

- Service: `App\Services\KaryawanReportService` (`rekapAdmin`, `rekapProduksi`, `rekapGudang`, `ringkasanKeuangan`, `rentangPeriode`).
- Role resolve: `RekapKaryawanController::roleKey()` memakai `Role::ADMIN/PRODUKSI/GUDANG`.
- Export: `OwnerRekapKaryawanExport` menerima parameter role + totals; skema kolom bercabang per role.
- Test: `KaryawanKpiTest` — 8 test (mapping role, parsing periode, metrik tiap role, konsistensi keuangan, penyempitan periode, render tiap filter).
