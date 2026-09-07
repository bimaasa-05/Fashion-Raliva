# Skills Installation Plan — Raliva Fashion

> **Sumber:** https://www.skills.sh/  
> **Tujuan:** Lengkapi workflow **analisa → verifikasi → saran kritis / problem solving** yang tadi terbukti perlu (contoh: `firstOrFail owner@raliva.test` vs `o@gmail.com`, `Collection::total/links` vs `LengthAwarePaginator`, `scrollbar-gutter: stable` anti geser).

---

## 1. Daftar Skill Wajib (7 Inti + 4 Pendukung)

### A. Inti Analisa & Verifikasi (Wajib — cegah “error salah analisa”)

| # | Nama di `skills.sh` | Deskripsi Singkat | Contoh di Raliva | File Bukti |
|---|---------------------|-------------------|------------------|------------|
| 1 | `evidence-before-synthesis` | Baca file dulu baru klaim; larang “sudah verifikasi” tanpa bukti | `Checkout.php:38` `orders()` plural vs `order()` singular — fix `Admin/Laporan:32` `checkout.orders` | `app/Models/Checkout.php:38` |
| 2 | `verification-qa` | `php -l` + `view:cache` + `route:list` + render `View::make()->render()` dengan `Auth::login` mock | QA 8 skenario `7hari/30hari` + `Collection::total` guard `instanceof AbstractPaginator` | `Owner/keuangan:188` |
| 3 | `root-cause-analysis` | 5 Why: stack `Builder:780 firstOrFail` → `OwnerSeeder:68` email mismatch `owner@raliva.test` vs `UserSeeder:17 o@gmail.com` | `migrate:fresh --seed` nonaktif | `database/seeders/RalivaDemoSeeder:88` |

### B. Saran / Analisa / Berpikir Kritis / Problem Solving (Yang diminta “listkan”)

| # | Nama | Deskripsi | Contoh Raliva |
|---|------|-----------|---------------|
| 4 | `critical-thinking` | Timbang tradeoff Opsi A/B/C dengan pros/cons, tidak validasi buta | `HPP 60%` statis `SaldoController:102` vs real HPP; `Admin→Owner→SuperAdmin` 1-stage vs 2-stage `pending_owner` |
| 5 | `problem-solving` | Solusi kreatif tanpa ubah HTML mutlak (`Admin/dashboard-operasional:128` `grid lg:grid-cols-3`) | `theme-head:322` `scrollbar-gutter: stable` + `min-h-[380px]` + `opacity-40` |
| 6 | `decision-matrix` | Matriks keputusan (biaya, risiko, UX) | `pemasukan` investor `WalletTransaction:179` `jenis='pemasukan'` → `pendapatan` (Opsi A) vs `pemasukan` terpisah (Opsi B rekomendasi) |

### C. Data & Bisnis Raliva

| # | Nama | Deskripsi |
|---|------|-----------|
| 7 | `data-analysis` | Bedakan `pendapatan` `Order.grand_total where selesai` `Laporan:25` vs `pemasukan` `WalletTransaction` `penjualan_masuk` `Saldo:73` vs `pemasukan_modal` |
| 8 | `security-scope` | Cek `AdminContext::assignedStoreIds()` leak `Stok/Komplain/Riwayat` (P1-1 fix) + harden `fallbackOwner` |
| 9 | `domain-modeling` | `Store operational_hours` hapus (24 jam) → migrasi `dropColumn` + `StoreSeeder` helper hapus |

### D. Pendukung Kolaborasi Plan → Build

| # | Nama | Deskripsi |
|---|------|-----------|
| 10 | `plan-to-build-handoff` | Tulis `file:line` detail (`Owner/pengajuan-toko:37` `step3Done`) agar `build` 1-to-1 |
| 11 | `explore-delegation` | Launch 3 sub-agent paralel (`Owner backend` + `Admin backend` + `shared gaps`) — sudah pakai `ses_f99dd...` |

---

## 2. Cara Install di https://www.skills.sh/

1. Buka `https://www.skills.sh/` → **Login** (GitHub/Google).
2. Search nama skill (ketik persis `evidence-before-synthesis`).
3. Klik **Install** → pilih workspace `opencode` (atau `C:\laragon\www\Raliva-Fashion`) → **Confirm**.
4. Skill muncul sebagai slash command `/evidence-before-synthesis` di chat OpenCode.
5. Ulangi untuk 11 skill di atas.

> **Bundle 7 Inti (rekomendasi instal dulu):**  
> `evidence-before-synthesis` → `verification-qa` → `root-cause-analysis` → `critical-thinking` → `problem-solving` → `data-analysis` → `security-scope`  
> (4 pendukung bisa menyusul: `domain-modeling`, `plan-to-build-handoff`, `explore-delegation`, `decision-matrix`).

---

## 3. Verifikasi Instalasi

- Di OpenCode chat, ketik `/` → daftar skill terinstal harus muncul 11 nama.
- Test cepat:
  ```bash
  /evidence-before-synthesis "cek Checkout::order vs orders"
  /verification-qa "php artisan view:cache"
  ```
- Jika skill belum muncul, `skills.sh` → `My Skills` → `Sync`.

---

## 4. Penggunaan ke Depan (Raliva)

- **Saat analisa:** `/evidence-before-synthesis` wajib `read` file dulu sebelum klaim `sudah verifikasi`.
- **Saat kasih saran:** `/critical-thinking` list Opsi A/B/C + `decision-matrix` (seperti `pemasukan` investor).
- **Saat fix:** `/verification-qa` jalankan `php -l` + `view:cache` + `route:list` + render mock (contoh QA `7hari` 8 skenario).
- **Saat problem solving:** `/problem-solving` cari solusi tanpa ubah HTML mutlak (contoh `scrollbar-gutter: stable`).

---

**Status:** Plan siap di `Docs/Planning/skills-installation-plan.md` + `Docs/plan/skills.md` (lowercase mirror). Instal 7 inti dulu untuk cover 90% bug `migrate:fresh --seed` nonaktif, `Collection::links`, `firstOrFail`, `scrollbar-gutter`.

**Next:** Instal di https://www.skills.sh/ lalu konfirmasi, atau lanjut **Pemasukan investor Opsi B** (rekomendasi) untuk `Owner/keuangan`?
