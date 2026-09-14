# Rencana Perbaikan — Eskalasi Komplain & Pengembalian Dana (Owner)

> Status: **disetujui user** — "gas kita buat seperti yang sudah direncanakan".
> Konteks: atur ulang visibilitas halaman **Owner** untuk Komplain & Pengembalian Dana agar hanya menampilkan item yang **dieskalasi Admin** kepadanya. Perbaiki juga ParseError pada halaman pengembalian-dana.
> Keputusan user (15 September 2026): (1) daftar Komplain Owner = HANYA status `escalated`, filter dropdown & kartu ringkasan DIHAPUS (minimalis); (2) setelah Owner memutuskan refund (Setujui/Tolak), refund **hilang dari daftar** (konsisten dengan komplain).

---

## Permasalahan

| # | Area | Masalah |
|---|---|---|
| 1 | Komplain | `Owner\KomplainController::index` menampilkan SEMUA komplain toko (termasuk `open`, `diproses`, `selesai`) → komplain yang belum dieskalasi bocor ke Owner. View punya 4 kartu ringkasan + banner prioritas + dropdown filter 5 opsi (tidak minimal). |
| 2 | Pengembalian Dana | `Owner\PengembalianDanaController::index` tab `pengajuan` = `requested` + `eskalasi` → refund yang belum dieskalasi tampil ke Owner. `setujui/tolak` juga menerima refund `requested`. |
| 3 | Pengembalian Dana (bug) | ParseError `resources\views\Owner\pengembalian-dana\index.blade.php:80 syntax error, unexpected token "endif"`. Akar masalah: baris sumber `...customer@if ($r->deskripsi_bukti_request)...@endif` — `@if` TIDAK dikompilasi Blade (tidak ada spasi/pemisah sebelum `@if`, jadi jadi teks literal) SEDANGKAN `@endif` setelah `</span>` dikompilasi → `endif` nyasar di PHP. |

**Sisi Admin tidak diubah** — mekanisme eskalasi sudah ada:
- `Admin\KomplainController::eskalasi()` → status komplain → `escalated` + notif Owner.
- `Admin\PengembalianDanaController::eskalasi()` → status refund → `eskalasi` + notif Owner.
- Alur dana refund (kredit saldo) tetap di `SuperAdmin\PengembalianDanaController` — tidak tersentuh.

---

## Perubahan Implementasi

### 1. Owner Komplain — hanya eskalasi
| File | Perubahan |
|---|---|
| `app/Http/Controllers/Owner/KomplainController.php` | `index()`: query `->where('status', Complaint::STATUS_ESKALASI)`, 1 nilai statistik `eskalasiCount` (untuk badge). Hapus `terbuka/menunggu/selesaiBulanIni/resolution`. |
| `app/Http/Controllers/Owner/KomplainController.php` | Guard defense-in-depth: `messages()`, `storeMessage()`, `updateMessage()`, `destroyMessage()` → `abort_unless($this->belongsToStoreEscalated($komplain), 404)` (cek toko + status harus `escalated`). Helper baru `belongsToStoreEscalated()`. |
| `resources/views/Owner/komplain/index.blade.php` | Hapus 4 kartu ringkasan, banner prioritas, dropdown filter. Badge header & subtitle baru ("Komplain yang dieskalasi Admin..."). Daftar carte disederhanakan: label status tetap "Eskalasi" (single badge gold). Modal detail + drawer chat dipertahankan (infrastruktur besar — hindari regresi). |

### 2. Owner Pengembalian Dana — hanya eskalasi + fix ParseError
| File | Perubahan |
|---|---|
| `app/Http/Controllers/Owner/PengembalianDanaController.php` | `index()`: query `Refund::where('status', Refund::STATUS_ESKALASI)` (eager `order/requester/reviewer/items`) + filter store, tanpa tabs/stats. |
| `app/Http/Controllers/Owner/PengembalianDanaController.php` | `setujui()`/`tolak()`: guard hanya `Refund::STATUS_ESKALASI`. |
| `resources/views/Owner/pengembalian-dana/index.blade.php` | Tulis ulang minimal: 1 seksi "Menunggu Keputusan Anda" (count badge + card refund eskalasi + modal Setujui/Tolak). Hapus 5 tab & status kustom; semua `@if` ditulis pada baris/posisi yang benar (ada spasi sebelum direktif) → ParseError hilang. |

### 3. Dokumen
- `Docs/plan/eskalasi-owner.md` (file ini).

---

## Verifikasi

1. `php -l` kedua controller Owner.
2. `php artisan view:clear` + `view:cache` → `view:cache` gagal = ada ParseError tersisa.
3. Render tinker (login Owner) + GET headless:
   - **Komplain**: komplain `escalated` tampil; komplain `open`/`diproses`/`selesai` TIDAK tampil; tidak ada dropdown filter & kartu ringkasan.
   - **Pengembalian Dana**: halaman render TANPA ParseError; refund `eskalasi` tampil dengan tombol Setujui/Tolak; refund `requested`/`disetujui`/`ditolak` TIDAK tampil.
   - **Aksi**: `setujui` & `tolak` refund eskalasi berhasil → hilang dari daftar → data test di-restore.
4. Selesai: `view:cache` terakhir + bersihkan skrip temp.

---

*Disusun: 15 September 2026 — kesepakatan keputusan minimalis & visibilitas hanya-eskalasi oleh user.*