# Revisi Produk: Penjelasan Lengkap (Kondisi Final)

Untuk: Admin, Gudang, Produksi, SuperAdmin, Owner (nonteknis).

> Dokumen `revisi-admin-produk.md` dan `revisi-produk-dua-tahap.md` menggambarkan
> fase lama dan **sudah tidak berlaku**. Acuan final = dokumen ini +
> `revisi-bahan-gudang-hpp.md` (detail teknis).

## Perjalanan singkat

1. **Awal**: form tambah raksasa — foto + info + target + resep + operasional + varian dalam 1 submit.
2. **Interim (2 tahap)**: sempat dipecah (tambah dulu, rencana belakangan) — ternyata masih ribet.
3. **Final (berlaku)**: form super-simple + bahan pindah ke Gudang.

## Alur final, simulasi nyata

Contoh: Admin mau jual **Blazer Wool Premium**.

**Langkah 1 — Admin isi form (1 popup, 1x simpan).** Urutan: Foto (1–5) →
Nama, Kategori, Tipe → **HPP/Modal `65.000`** → **Harga Jual `150.000`** →
Deskripsi → Ukuran, Warna (boleh kosong), Stok per varian → Simpan.
Produk berstatus **Menunggu** (moderasi SuperAdmin).

**Langkah 2 — Gudang otomatis dihubungi.** Begitu Admin submit, Gudang se-toko
dapat notifikasi *"Produk Baru Perlu Bahan"*. Gudang buka menu **Bahan Produk**
→ produk tanpa bahan ada paling atas ("Belum ada") → Input Bahan → tambah
baris: `Kancing × 4 pcs`, `Kain Katun × 2 meter` → Simpan.

**Langkah 3 — Pesanan jalan, Produksi terima beres.** Customer pesan 2 pcs →
Produksi tekan **Accept** → sistem otomatis menyalin resep jadi kebutuhan
pesanan: `Kancing 4×2 = 8 pcs`, tampil sebagai **"Bahan dari Admin"**.
Kurang bahan? Produksi tambah sendiri per pesanan ("Tambahan Produksi").

**Langkah 4 — Edit & moderasi.** Admin ubah nama/HPP/harga/foto/varian →
proposal → SuperAdmin bandingkan sebelum vs sesudah (termasuk baris **HPP**
dan **Margin**) → Setujui/Tolak (tolak wajib alasan ≥10 karakter).
Bahan milik Gudang **tidak tersentuh** approval.

## Aturan main yang berlaku

| Aturan | Isi |
|---|---|
| HPP/Modal | Wajib, minimal Rp 1, diketik manual sebelum Harga Jual |
| Margin | Otomatis: harga − HPP (mis. 150.000 − 65.000 = Rp 85.000 ≈ 56,67%) |
| Target & biaya operasional | **Dihapus** dari alur Admin (data lama tetap tersimpan) |
| Bahan | Wajib ≥1 baris saat Gudang isi; nama ≤150 karakter, jumlah ≥0,001; satuan meter/cm/yard/roll/kg/gram/pcs |
| Foto | 1–5 file, JPG/PNG/WebP ≤2 MB; edit pakai centang-hapus + staging, total ≤5 |
| Warna | Opsional; custom wajib nama bermakna + hex valid (`Warna 1` ditolak) |
| Stok | Minimal 1 per varian; tersimpan di gudang aktif (Gudang Utama dibuatkan bila belum ada) |
| Slot | Kuota penuh = tambah produk ditolak sampai beli slot |

## Dulu vs sekarang

| Dulu | Sekarang |
|---|---|
| 1 form raksasa (7 concern, 235 baris) | 1 form simple (master saja) |
| Resep + target + operasional wajib di Admin | Resep milik Gudang; target/operasional dihapus |
| HPP dihitung otomatis | HPP diketik manual; margin otomatis |
| Modal rencana + modal Edit + approval berisi resep | Bersih dari resep; approval hanya master + HPP |
| Produksi tidak tahu bahan sampai input manual | Bahan tersalin otomatis saat Accept |
| Gudang tidak tahu produk baru | Notifikasi + menu Bahan Produk |

## Status verifikasi

- Otomatis: **67 passed + 2 skipped** (skip = DB lokal tinggal 1 toko, butuh toko kedua untuk uji scope; bukan bug).
- `php -l`, `view:cache/clear`, `git diff --check`: bersih.
- Sisa: verifikasi browser manual 3 role + mobile.
- Pintu depan: bila butuh biaya bahan, kolom `biaya_per_unit` sudah ada di DB — tinggal tambah 1 field di form Gudang + aktifkan koreksi modal otomatis.
