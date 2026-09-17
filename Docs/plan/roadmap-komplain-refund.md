# Roadmap Komplain & Refund — Raliva Fashion

> Dibuat: 2026-09-17
> Sumber perencanaan utama untuk sistem **komplain**, **refund**, dan kaitannya dengan **rekap karyawan** + **saldo pelanggan**. Setiap area punya dokumen detail sendiri.

---

## 1. Status Keseluruhan

### Fitur Sudah Ada (terlacak di kode)

- **Sistem Komplain** — selesai & tersambung DB (customer buat → admin balas/eskalasi → owner tangani eskalasi → SuperAdmin tutup). Detail: `alur-komplain-lengkap.md`.
- **Sistem Refund** — selesai & tersambung DB (customer ajukan → admin setujui/tolak/eskalasi → owner tangani eskalasi → Owner/SA selesaikan). Detail: `alur-refund-dan-saldo.md`.
- **Rekap Karyawan** (Owner + blok "Penjualanku" Admin) — selesai & terverifikasi runtime; injak atribusi refund masih menyisakan perbaikan kecil. Detail: `refund-rekap-karyawan.md`.

### Fitur Rencana

| # | Batch | Nama | Status | Estimasi |
|---|---|---|---|---|
| A | — | Perbaikan atribusi refund rekap karyawan (`reviewed_by` tetap admin + hitung `selesai` saja) | Rencana disusun | ~1–2 jam |
| 1 | Integrasi | Kolom `refunds.complaint_id` + relasi | Rencana disusun | ~1 jam |
| 2 | Integrasi | Tombol "Ajukan Refund" dari thread komplain | Rencana disusun | ~2 jam |
| 3 | Integrasi | Konsistensi `selesaikan` antar role (opsi A/B) | Perlu keputusan | ~1–2 jam |
| 4 | Integrasi | Transisi `Order::STATUS_REFUND` + dampak laporan | Perlu kajian | ~2–3 jam |
| 5 | Saldo | Sistem saldo pelanggan (ala beautycare) | Rancangan (§5) | TBD |

---

## 2. Keputusan Kunci (sudah disepakati)

| Topik | Keputusan |
|---|---|
| Status yang dihitung untuk rekap | Refund dihitung hanya status **`selesai`** (dana benar-benar keluar). `disetujui` tidak. |
| Atribusi eskalasi refund | `reviewed_by` **tetap admin penangan awal**; Owner setujui/tolak **tidak menimpa**. |
| Penutupan komplain | Saat ini **eksklusif SuperAdmin** (`tutup`). Desentralisasi ke Owner belum diputuskan. |
| Komplain ↔ refund | Dua entitas yang bisa berdiri sendiri; integrasi dilakukan lewat `complaint_id`, bukan penggabungan status. |
| Kelola `Order::STATUS_REFUND` | Belum dipakai sebagai transisi; hanya dijalankan setelah kajian dampak laporan pendapatan (batch 4). |
| Sistem saldo pelanggan | Ditulis sebagai rancangan/proposal dulu (`alur-refund-dan-saldo.md` §5); keputusan desain menyusul sebelum eksekusi. |

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

1. **Batch A — Rekap Karyawan** (`refund-rekap-karyawan.md`): kunci keputusan "refund `selesai` saja" + "`reviewed_by` tetap admin". Paling kecil & sudah final.
2. **Batch Integrasi 1–2**: migrasi `complaint_id` + tombol refund dari thread komplain.
3. **Batch Integrasi 3**: putuskan opsi A/B konsistensi `selesaikan`.
4. **Batch Integrasi 4**: transisi `Order::STATUS_REFUND` (setelah kajian dampak laporan).
5. **Batch Saldo**: sistem saldo pelanggan (menunggu keputusan desain).

---

## 5. Aturan Penulisan Dokumen Plan

- Semua file plan di `C:\laragon\www\Raliva-Fashion\Docs\plan`, nama kebab-case tanpa angka awalan (kecuali roadmap boleh `roadmap-*.md`).
- Gaya mengikuti dokumen alur yang sudah ada (`Docs/penjelasan-*.md`, `Docs/alur-website.md`): tabel status, diagram alur, detail per tahap dengan referensi file/baris, role & aksi, temuan jujur, batasan.