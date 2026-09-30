# Penjelasan Revisi: Popup Tambah Slot & Syarat Dokumen Legal

Cakupan: popup **Tambah Slot** Owner (2 opsi), popup gate kuota Admin (2 opsi),
syarat wajib **KTP / NIB / NPWP terverifikasi**, overlay popup yang lebih terang,
banner sukses Pengajuan Toko hijau.

Keputusan dari Owner (konfirmasi chat): Tambah Slot Owner memakai popup dengan
2 opsi; tidak ada pengukuran persentase overlay; alur sidebar & halaman Kelola
Slot tidak dihapus dan tidak diubah.

---

## 1. Ringkasan — masalah vs solusi

| Sebelum | Sesudah |
|---|---|
| Tombol **Tambah Slot** Owner pindah ke halaman Kelola Slot. | Membuka **popup 2 opsi**: Beli Paket Slot / Kelola Slot; form Kelola Slot dibuka **di dalam popup itu juga**. |
| Admin kuota habis + klik Tambah → form produk langsung dibuka, lalu gagal di server. | Muncul **popup gate 2 opsi**: Beli Slot (form di popup) / Beli Paket (ke `admin.slot#paket`). Form produk tidak dibuka. |
| Beli slot tidak punya syarat dokumen. | Wajib minimal **satu** dari KTP / NIB / NPWP berstatus `terverifikasi`. |
| Overlay popup `bg-black/50` (gelap). | Popup baru memakai `bg-black/35 backdrop-blur-sm` (lebih terang). |
| Banner sukses Pengajuan Toko kuning (`text-secondary`). | Banner sukses hijau (`text-success`); error tetap merah. |

---

## 2. Alur Owner

```
owner.produk (Data Produk)
   │ klik "Tambah Slot"
   ▼
modal-pilih-slot  ── badge syarat legal (hijau OK / merah belum)
   ├─ "Beli Paket Slot" ──▶ halaman owner.paket-slot
   └─ "Kelola Slot"     ──▶ modal-kelola-slot (di atas chooser)
                                │ form: jumlah + metode + bukti + alasan
                                │ + total live (JS ms-jumlah/ms-total)
                                ▼
                        POST owner.kelola-slot.request
                                │ 1) gate DokumenLegal (ditolak → error+banner)
                                │ 2) validasi form
                                ▼
                        SlotPurchaseRequest status pending
                                ▼
                        SuperAdmin setujui → grant → kuota bertambah
```

Detail perilaku:

1. Tombol **Tambah Slot** berada di `Owner/produk/index.blade.php:44`
   (`data-modal-open="modal-pilih-slot"`). Partial popup di-include lewat
   `@push('modals')` agar ter-render di `@stack('modals')` layout Owner
   (`layouts/owner.blade.php:272`).
2. Popup chooser (`Owner/partials/modal-slot.blade.php`, `#modal-pilih-slot`)
   menampilkan sisa/maksimal slot plus **badge syarat legal**: hijau dengan
   ikon `verified` bila syarat terpenuhi (menyebutkan dokumen yang ada,
   mis. "NIB"), merah dengan ikon `gpp_bad` + link ke Pengajuan Toko bila
   belum terpenuhi.
3. Form **Beli Slot Fleksibel** (`#modal-kelola-slot`) berisi Jumlah Slot
   (1–1000), Metode Pembayaran, Bukti Pembayaran (JPG/PNG/PDF maks 4 MB),
   Alasan opsional, dan **total bayar hitung-live** (`Rp jumlah × harga/slot`,
   harga dari `SlotService::hargaPerSlot()` = Rp 2.000). Error validasi form
   membuat popup ini terbuka otomatis saat halaman dimuat
   (`Owner/produk/index.blade.php:162`).
4. Penutupan popup: tombol X, tombol Escape, klik area gelap
   (mekanisme `data-modal-open` / `data-modal-close` di layout Owner).
5. Bila toko belum ada sama sekali: tombol ikut nonaktif oleh script lama
   halaman itu (banner `data-no-store-banner` → semua tombol di `[data-real]`
   di-disable kecuali Ajukan Toko).

---

## 3. Alur Admin

```
admin.produk, kuota HABIS (SlotService::canAdd = false)
   │ tombol "Tambah" → buka modal-slot-habis (BUKAN form produk)
   ▼
modal-slot-habis ("Slot Produk Penuh", angka terpakai/total)
   ├─ "Beli Slot" ──▶ modal-slot-beli (form di popup)
   │                      │ pilih toko + jumlah + metode + bukti
   │                      │ + total live (JS as-jumlah/as-total)
   │                      ▼
   │                   POST admin.slot.request → pending → SuperAdmin approve
   └─ "Beli Paket" ──▶ admin.slot#paket (kuota langsung aktif di halaman itu)
```

Detail perilaku:

1. Tombol **Tambah** bersifat kondisional: kuota habis → trigger
   `data-modal-open="modal-slot-habis"`; kuota tersedia → trigger form
   produk seperti biasa (`#modal-form-produk`).
2. Partial: `Admin/partials/modal-slot.blade.php` (`#modal-slot-habis`,
   `#modal-slot-beli`), di-include lewat `@push('modals')`.
3. Pembuka otomatis (`Admin/produk/index.blade.php:1496`): parameter
   `?slot_habis=1` membuka chooser; adanya validation error field slot
   (`store_id`, `jumlah_slot`, `metode_pembayaran`, `file_bukti`, `alasan`)
   membuka form Beli Slot langsung.
4. Section paket di halaman Beli Slot Admin diberi anchor `id="paket"` agar
   tombol **Beli Paket** loncat tepat ke bagian paket.

---

## 4. Validasi baru `DataProdukController@store`

Urutan di `store()` **sengaja: validasi dulu, gate kuota sesudahnya**:

1. Validasi form produk berjalan normal → form tidak valid = error validasi
   biasa (toast), popup slot **tidak** dibuka.
2. Form valid + kuota habis → redirect ke `admin.produk?slot_habis=1` dengan
   `withInput()` (input tidak hilang) + pesan
   `Kuota slot produk penuh (X/Y). Pilih Beli Slot atau Beli Paket di bawah.`
   → popup chooser terbuka otomatis.

Kenapa gate TIDAK di paling atas: versi pertama menaruh gate sebelum
validasi, dan itu menelan error form — 2 test `ProductRecipeTest` ("master
requires hpp", "master rejects stock below minimum") merah karena request
tidak-valid ikut di-redirect tanpa `$errors`. Gate dipindah ke bawah validasi
(commit `5f5fb6b9`); kedua test hijau kembali.

POST langsung ke endpoint saat kuota habis tetap ditolak server-side (tidak
bisa diakali dengan bypass tombol UI).

---

## 5. Syarat dokumen legal

Aturan: **minimal SATU** dari KTP, NIB, atau NPWP berstatus `terverifikasi`.

- Helper: `App\Support\DokumenLegal` — konstanta jenis (`ktp`, `siu`, `npwp`),
  `satisfied($storeId)`, `jenisTerverifikasi()`, `namaTersedia()`,
  `pesanKurang()`.
- **Catatan NIB:** di tabel `store_documents`, NIB disimpan sebagai baris
  `jenis='siu'` (label tampil "Surat Izin Usaha (NIB)"). Helper memetakan
  label `siu` → `NIB` agar banner popup menulis "NIB", bukan "SIU".
- Penegakan ganda:
  - **UI** (`Owner/partials/modal-slot`): badge hijau/merah; seluruh field +
    tombol submit form Kelola Slot di-`disabled` bila syarat belum terpenuhi,
    lengkap dengan pesan + link **lengkapi dokumen di Pengajuan Toko**.
  - **Server** — ditolak dengan `back()->withInput()->with('error', ...)`:
    `Owner/KelolaSlotController.php:106` (`store`) dan
    `Owner/PaketSlotController.php:83` (`purchase`).
- Data untuk popup disiapkan di `Owner/ProdukController@index`:
  `$dokLegalOk`, `$dokLegalAda`, `$metodeSlot`, `$hargaPerSlot`.
- Cara Owner memenuhi syarat: Pengajuan Toko → unggah KTP/NIB/NPWP →
  SuperAdmin verifikasi → status `terverifikasi`.

---

## 6. Perubahan kecil lain

- Overlay popup baru: `bg-black/35 backdrop-blur-sm`. Modal lama tidak
  disentuh.
- Banner sukses `Owner/pengajuan-toko`: `text-secondary` → hijau
  (`text-success` + `border-success/20 bg-success/10`). Banner error tetap
  merah.

---

## 7. Detail per file

| File | Peran |
|---|---|
| `app/Support/DokumenLegal.php` | **Baru.** Helper syarat minimal KTP/NIB/NPWP terverifikasi. |
| `app/Http/Controllers/Owner/KelolaSlotController.php` | Gate legal di `store()` (sebelum validasi). |
| `app/Http/Controllers/Owner/PaketSlotController.php` | Gate legal di `purchase()` (sebelum validasi). |
| `app/Http/Controllers/Owner/ProdukController.php` | `index()` siapkan kuota (`$totalSlot`, `$sisaSlot`, ...) + flag legal + metode + harga slot untuk popup. |
| `resources/views/layouts/owner.blade.php` | Tambah `@stack('modals')` (baris 272). |
| `resources/views/Owner/partials/modal-slot.blade.php` | **Baru.** Chooser `modal-pilih-slot` + form `modal-kelola-slot` + JS total live. |
| `resources/views/Owner/produk/index.blade.php` | Tombol Tambah Slot jadi trigger popup; include partial; auto-open form saat validation error. |
| `resources/views/Owner/pengajuan-toko/index.blade.php` | Banner sukses kuning → hijau. |
| `app/Http/Controllers/Admin/DataProdukController.php` | `index()` siapkan `$slotKuota`, `$slotHabis`, `$slotStores`, `$slotMetode`, `$slotHarga`; `store()` gate kuota setelah validasi → redirect `?slot_habis=1` + `withInput()`. |
| `resources/views/Admin/partials/modal-slot.blade.php` | **Baru.** Chooser `modal-slot-habis` + form `modal-slot-beli` + JS total live. |
| `resources/views/Admin/produk/index.blade.php` | Tombol Tambah kondisional; include partial; auto-open chooser/form. |
| `resources/views/Admin/slot/index.blade.php` | Anchor `id="paket"` untuk tombol Beli Paket. |
| `tests/Feature/SlotHabisTest.php` | Rewrite test gate lama + 7 test baru (kuota, popup, legal, banner). |

---

## 8. Bukti uji

- `SlotHabisTest` — **12 passed, 51 assertions**:
  full quota redirects to produk with slot modal; produk index shows slot
  gate modal when quota full; produk index shows create modal when quota
  available; admin can buy package instantly; admin package purchase rejects
  foreign store; slot request approve grants and reject grants nothing; slot
  approve is idempotent; owner produk shows slot popup options; owner popup
  requires verified legal document; owner popup allows purchase with
  verified nib; owner paket requires verified legal document; pengajuan toko
  success banner is green.
- Regresi terkait (`GudangBahanTest`, `ProductFormPolishTest`,
  `ProductColorValidationTest`, `ProductRecipeTest`,
  `ProductUpdateWorkflowTest`) — **41 passed + 1 skipped** (skip bawaan,
  bukan dari perubahan ini), 195 assertions.
- `php -l` semua file PHP yang diubah, `php artisan view:cache` +
  `view:clear`, `git diff --check` — bersih.

---

## 9. Batasan jujur (diketahui, belum diperbaiki)

1. Kartu **Beli Paket Slot** di popup Owner tetap bisa diklik saat syarat
   legal belum terpenuhi — penolakan terjadi di server
   (`PaketSlotController.php:83`) dengan pesan error. Hanya form Kelola Slot
   yang di-disable di UI.
2. Popup Owner **tidak terbuka otomatis** saat gate server menolak (hanya
   banner error di halaman, karena tidak ada validation `$errors`).
3. Handler modal Owner terpasang **2×** di `layouts/owner.blade.php`
   (baris 277 dan 337) — tidak merusak fungsi, tapi layak dirapikan.
4. Gate Admin hanya mengevaluasi **toko pertama** penugasan
   (`AdminContext::assignedStoreIds()[0]`), sama seperti perilaku form produk
   sebelumnya.

---

## Lampiran — commit fitur ini (17 commit, 1 file 1 commit, tanpa push)

`717997d7` Helper DokumenLegal · `93e6f8e5` Gate legal KelolaSlot ·
`28268fea` Gate legal PaketSlot · `2ed870bd` index Owner siapkan data popup ·
`ac03f6c3` stack modals layout Owner · `aaac5748` partial popup Owner ·
`c498ef55` Tombol Slot Owner buka popup · `b7df89e1` banner hijau Pengajuan ·
`c7ecbdb4` index Admin siapkan data popup · `eed5b384` partial popup Admin ·
`c8ad0803` tombol Tambah Admin kondisional · `2820317b` anchor `#paket` ·
`99638f12` test (rewrite + 7 baru) · `ff6698d0` + `5f09aa7f` doc ringkas batch B ·
`5f5fb6b9` gate kuota setelah validasi · `9e822239` doc posisi gate.
