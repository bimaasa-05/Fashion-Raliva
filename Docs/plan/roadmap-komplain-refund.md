# Roadmap Komplain & Refund — Raliva Fashion

> Dibuat: 2026-09-17
> Sumber perencanaan utama untuk sistem **komplain**, **refund**, dan kaitannya dengan **rekap karyawan** + **saldo pelanggan**. Setiap area punya dokumen detail sendiri.

---

## 1. Status Keseluruhan

### Fitur Sudah Ada (terlacak di kode)

- **Sistem Komplain** — selesai & tersambung DB (customer buat → admin balas/eskalasi → owner tangani eskalasi → SuperAdmin tutup). Detail: `alur-komplain-lengkap.md`.
- **Sistem Refund** — selesai & tersambung DB (customer ajukan → admin setujui/tolak/eskalasi → owner tangani eskalasi → Owner/SA selesaikan). Detail: `alur-refund-dan-saldo.md`.
- **Rekap Karyawan** (Owner + blok "Penjualanku" Admin) — selesai & terverifikasi runtime; perbaikan atribusi refund sudah dijalankan (Batch A). Detail: `refund-rekap-karyawan.md`.
- **Batch Integrasi 1–2** (kolom `refunds.complaint_id` + tombol "Ajukan Refund" dari thread komplain) — selesai & terverifikasi.
- **Batch Integrasi 3** (konsistensi `selesaikan` satu jalur via service) — selesai.
- **Batch Integrasi 4** (transisi `Order::STATUS_REFUND` + penyesuaian laporan pendapatan) — selesai & terverifikasi soak.

### Fitur Rencana

| # | Batch | Nama | Status | Estimasi |
|---|---|---|---|---|
| A | — | Perbaikan atribusi refund rekap karyawan (`reviewed_by` tetap admin + hitung `selesai` saja) | ✅ Selesai | ~1–2 jam |
| 1 | Integrasi | Kolom `refunds.complaint_id` + relasi | ✅ Selesai | ~1 jam |
| 2 | Integrasi | Tombol "Ajukan Refund" dari thread komplain | ✅ Selesai | ~2 jam |
| 3 | Integrasi | Konsistensi `selesaikan` antar role: **satu jalur sama persis** via `RefundCompletionService` (decrement wallet + kredit saldo akun customer + bukti) | ✅ Selesai | ~2 jam |
| 4 | Integrasi | Transisi `Order::STATUS_REFUND` (full refund) + dampak laporan (`whereIn [selesai, refund]`) | ✅ Selesai | ~2–3 jam |
| 5 | Saldo | Sistem saldo pelanggan (ala beautycare) — kredit otomatis saat `selesaikan` bila bayar via saldo akun | ✅ Bagian inti selesai (kredit otomatis); UI penarikan/batas saldo menyusul | — |
| 6 | — | Scope `AdminContext` pada `Admin/PengembalianDanaController` | ✅ Selesai | ~1 jam |

---

## 2. Keputusan Kunci (sudah disepakati)

| Topik | Keputusan |
|---|---|
| Status yang dihitung untuk rekap | Refund dihitung hanya status **`selesai`** (dana benar-benar keluar). `disetujui` tidak. **✅ Dieksekusi.** |
| Atribusi eskalasi refund | `reviewed_by` **tetap admin penangan awal**; Owner setujui/tolak **tidak menimpa**. **✅ Dieksekusi.** |
| Scope Admin | `Admin\PengembalianDanaController` (index + semua aksi) dibatasi ke toko di `AdminContext::assignedStoreIds()`. **✅ Dieksekusi.** |
| `selesaikan` antar role | **Satu jalur sama persis** (keputusan user): Owner & SA memanggil `RefundCompletionService::complete` — potong wallet toko (`JENIS_REFUND_KELUAR`), wajib bukti, kredit saldo akun customer bila dibayar saldo akun, error rollback (file dihapus). **✅ Dieksekusi.** |
| Transisi `Order::STATUS_REFUND` | **AKTIF sekarang** (keputusan user membalik rekomendasi awal). Order di-set `refund` saat refund **full** `selesai` dengan `jumlah >= grand_total`. Model keuangan: laporan **dengan baris Refund** memakai `whereIn(status,[selesai,refund])` di sisi pendapatan (net = 0, anti double-deduct); statistik **tanpa baris Refund** tetap `selesai` (order refund gugur alami). **✅ Dieksekusi.** |
| Penutupan komplain | Saat ini **eksklusif SuperAdmin** (`tutup`). Desentralisasi ke Owner belum diputuskan. |
| Komplain ↔ refund | Dua entitas yang bisa berdiri sendiri; integrasi dilakukan lewat `complaint_id`, bukan penggabungan status. |
| Sistem saldo pelanggan | Inti kredit otomatis sudah jalan (`CustomerWalletService::refundToWallet`) saat `selesaikan` untuk payment `KODE_SALDO_AKUN`. Sisa rancangan (UI Saldo, penarikan) mengikuti desain `alur-refund-dan-saldo.md` §5. |

---

## 3. Hierarki Dokumen

```
Docs/plan/
├── roadmap-komplain-refund.md              ← kamu di sini (peta + keputusan)
├── alur-komplain-lengkap.md                ← penjelasan sistem komplain (kondisi sekarang)
├── alur-refund-dan-saldo.md                ← penjelasan sistem refund + proposal saldo pelanggan
├── integrasi-komplain-refund.md            ← rencana batch penyambungan komplain↔refund
└── refund-rekap-karyawan.md                ← plan perbaikan atribusi refund di rekap karyawan
```

---

## 4. Urutan Kerja yang Diusulkan

1. **Batch A — Rekap Karyawan** (`refund-rekap-karyawan.md`): kunci keputusan "refund `selesai` saja" + "`reviewed_by` tetap admin". ✅ Selesai.
2. **Batch Integrasi 1–2**: migrasi `complaint_id` + tombol refund dari thread komplain. ✅ Selesai.
3. **Batch Integrasi 3**: konsistensi `selesaikan` → satu jalur via service. ✅ Selesai.
4. **Batch Integrasi 4**: transisi `Order::STATUS_REFUND` + penyesuaian laporan (`whereIn [selesai, refund]` untuk laporan ber-baris Refund). ✅ Selesai.
5. **Batch Saldo**: kredit otomatis sudah jalan; UI manajemen saldo menyusul (menunggu keputusan desain).

---

## 5. Aturan Penulisan Dokumen Plan

- Semua file plan di `C:\laragon\www\Raliva-Fashion\Docs\plan`, nama kebab-case tanpa angka awalan (kecuali roadmap boleh `roadmap-*.md`).
- Gaya mengikuti dokumen alur yang sudah ada (`Docs/penjelasan-*.md`, `Docs/alur-website.md`): tabel status, diagram alur, detail per tahap dengan referensi file/baris, role & aksi, temuan jujur, batasan.