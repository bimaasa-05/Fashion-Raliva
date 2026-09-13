# Penjelasan Alur: Koordinasi Gudang, Permintaan Produksi & Data Supplier

Dokumen ini menjelaskan tiga fitur menu **Logistik (Admin)** — dari mana datanya berasal, siapa yang bertindak, ke mana datanya mengalir, dan bagaimana ketiganya terhubung dalam satu rantai operasional Raliva Fashion.

> Istilah penting:
> - **Admin** = Admin Toko (pengelola operasional harian).
> - **Gudang** = staf gudang (konfirmasi stok, terima/kirim barang).
> - **Produksi** = tim produksi (membuat barang).
> - **Owner** = pemilik toko (menerima notifikasi & laporan).

---

## 1. Koordinasi Gudang

### 1.1. Tujuannya apa?

Menjawab pertanyaan: *"Pesanan yang sudah dibayar pelanggan, barangnya diambil dari gudang mana, dan stoknya cukup atau harus dipindah/diproduksi dulu?"*

Halaman ini punya dua fungsi:

- **A. Permintaan Pengambilan** — Admin meminta gudang menyiapkan barang untuk pesanan tertentu.
- **B. Pemindahan Stok** — memindahkan stok antar gudang dalam toko yang sama.

### 1.2. Alur A — Permintaan Pengambilan (langkah demi langkah)

```
┌─────────────┐     ┌──────────────────┐     ┌─────────────────────┐
│  PESANAN    │     │ KOORDINASI GUDANG │     │  GUDANG (staf)      │
│  dibayar /  │───▶ │  (Admin)          │───▶│                     │
│  diproses   │     │                   │     │                     │
└─────────────┘     └──────────────────┘     └─────────────────────┘
      │                       │                           │
      │  1. Muncul otomatis   │  2. Klik                  │  3. Terima
      │     di daftar         │     "Kirim Permintaan"    │     notifikasi,
      │     "Perlu Diambil    │                           │     siapkan
      │      di Gudang"       │                           │     barang
```

1. **Dari mana daftarnya?** Setiap pesanan berstatus `dibayar`/`diproses` di toko yang ditugaskan ke Admin otomatis tampil di daftar *"Pesanan Perlu Diambil di Gudang"*.
2. **Apa yang dilakukan Admin?** Klik **Kirim Permintaan** → sistem membuat catatan `StockTransfer` (gudang asal → gudang tujuan, beserta item & jumlahnya) dan **mengirim notifikasi ke staf Gudang**.
3. **Ke mana mengalir?** Permintaan masuk antrean **Pemindahan Stok** di halaman Gudang dan mengikuti siklus hidup di bawah ini. Setelah barang siap, alur dilanjutkan Admin di halaman **Pengiriman** (input resi → kirim).

### 1.3. Alur B — Siklus hidup Pemindahan Stok

```
requested (diajukan)
   │  ▲ gudang asal: Batalkan
   ▼  │ (stok dikembalikan bila sudah terpotong)
approved (disetujui gudang tujuan → stok asal berkurang, tercatat mutasi keluar)
   │  ▲ gudang asal: Batalkan
   ▼  │
received (diterima gudang tujuan → stok tujuan bertambah, tercatat mutasi masuk)
   │
   ● selesai (tidak bisa diubah lagi)
```

| Aksi | Siapa | Syarat | Efek ke stok |
|---|---|---|---|
| Buat request | Gudang asal / Admin | Stok asal cukup (dicek dulu) | Belum ada (baru catatan) |
| Setujui | **Gudang tujuan** | Status `requested` | Stok asal **berkurang** + mutasi keluar |
| Terima Barang | **Gudang tujuan** | Status `approved` | Stok tujuan **bertambah** + mutasi masuk + waktu terima |
| Batalkan | **Gudang asal** | Status `requested`/`approved` | Bila sudah terpotong, stok **dikembalikan** + mutasi penyeimbang |

Setiap langkah dikunci database (`lockForUpdate`), dicatat di log aktivitas, dan Admin menerima notifikasi pada saat disetujui & diterima. Aturan gampangnya: **yang menyetujui & menerima = gudang tujuan; yang membatalkan = gudang asal.**

---

## 2. Permintaan Produksi

### 2.1. Tujuannya apa?

Menjawab pertanyaan: *"Stok tidak cukup untuk pesanan — siapa yang membuat barangnya, berapa banyak, dan kapan selesai?"*

### 2.2. Alur lengkap (langkah demi langkah)

```
┌──────────────┐    ┌────────────────────┐    ┌──────────────────────┐    ┌────────────┐
│ ADMIN        │    │ PRODUKSI (tim)     │    │ STOK GUDANG          │    │ PENGIRIMAN │
│ buat         │───▶│ kerjakan           │───▶│ bertambah            │───▶│ lanjut     │
│ permintaan   │    │ bertahap           │    │ otomatis             │    │ kirim      │
└──────────────┘    └────────────────────┘    └──────────────────────┘    └────────────┘
```

1. **Dari mana?** Admin mengisi form (varian produk, jumlah, gudang target, prioritas, order terkait bila ada) di halaman **Permintaan Produksi (Admin)**. Sistem membuat `ProductionOrder` bernomor `PRD-...` berstatus `requested` + notifikasi ke tim **Produksi**.
2. **Dikerjakan bertahap** di halaman **Permintaan Produksi (Produksi)** — status hanya boleh maju selangkah dan terkunci sistem:
```
requested → diproses → menunggu_qc → selesai
   (dari titik mana pun sebelum selesai boleh: dibatalkan)
```
3. **Saat `selesai`**, sistem OTOMATIS (tanpa input manual):
   - membuat `ProductionResult` (jumlah jadi),
   - **menambah stok** tiap varian di gudang target,
   - mencatat `StockMovement` masuk (`SUMBER_PRODUCTION_RESULT`),
   - memberi tahu Admin.
4. **Ke mana?** Stok baru ini dipakai **Pengiriman** seperti stok biasa. Inilah titik barang "lahir" di sistem.

---

## 3. Data Supplier

### 3.1. Tujuannya apa?

Master data pemasok bahan/aksesoris/kemasan: nama, kontak, email, kota, jenis, status (`aktif` / `nonaktif` / `verifikasi`). Lengkap dengan operasi tambah–ubah–hapus dan kartu statistik.

### 3.2. Mengalir ke mana?

Supplier **tidak menciptakan stok sendiri**. Ia dikonsumsi satu arah oleh **Gudang → Barang Masuk**:

```
ADMIN isi Data Supplier
        │ (pilih saat catat barang masuk)
        ▼
GUDANG → Barang Masuk: pilih supplier + jumlah
        │
        ├─▶ WarehouseStock bertambah (tercatat supplier_id-nya)
        └─▶ StockMovement masuk (SUMBER_SUPPLIER)
```

Artinya: kolom *Supplier* di halaman Stok menjawab *"barang ini datang dari siapa"*, sedangkan produksi menjawab *"barang ini dibuat kapan"*. Dua-duanya bermuara ke stok yang sama.

---

## 4. Peta rantai end-to-end (satu gambar besar)

```
Customer checkout (menunggu bayar)
  │ Admin: Verifikasi Pembayaran → setujui
  ▼
Order = dibayar
  │ Admin: Data Pesanan → Proses
  ▼
Order = diproses
  │ Gudang: konfirmasi ketersediaan
  ├─ TERSEDIA ──▶ Admin: Koordinasi Gudang (ambil)
  │                    │
  │                    ▼
  │                Admin: Pengiriman (input resi → kirim = dikirim)
  │                    │
  │                    ▼
  │                Customer konfirmasi terima = selesai (+ dompet owner)
  │
  └─ KURANG ──▶ Admin: Permintaan Produksi (requested)
                     │
                     ▼
                Produksi: diproses → QC → selesai (+ stok otomatis)
                     │
                     └────▶ kembali ke Pengiriman ☝
Bahan dari luar: Admin: Data Supplier ──▶ Gudang: Barang Masuk (+ stok)
Antar gudang: request → setujui → terima (stok pindah tercatat dua sisi)
```

## 5. Catatan jujur (yang belum / batasan sadar)

1. **Alamat tujuan** belum tersimpan di database mana pun — label "Penerima" menampilkan nama customer. Bila ingin alamat asli di halaman Pengiriman, perlu kolom baru (migrasi).
2. Halaman **Data Produksi (Produksi)** masih tampilan contoh — antrean kerja yang nyata dan beraksi ada di **Permintaan Produksi**.
3. Foto bukti retur customer (`file_bukti_request`) tersimpan terpisah dari bukti transfer penyelesaian (`file_bukti`) sehingga keduanya tidak saling menimpa.

---

*Terakhir diperbarui: 12 September 2026 — mencakup kondisi setelah Fase 2 (approve/receive transfer, UI Produksi real, bukti retur).*
