# Revisi Owner Batch A — Dokumen & Pengajuan Toko

Scope: file Owner (+ SuperAdmin/ManajemenToko untuk verifikasi).
Keputusan: minimal 3 file bebas kombinasi; tanpa UI SuperAdmin baru.

## A1 — Minimal 3 file + perbaikan create

- [ ] `PengajuanTokoController@store`: hitung dokumen existing-valid + file baru,
      tolak jika total < 3 (pesan jelas).
- [ ] Simpan `kategori` saat `Store::create` (bug: divalidasi tapi hilang).
- [ ] Hapus file lama di storage saat dokumen di-overwrite.
- [ ] Migrasi `unique[store_id,jenis]` di `store_documents`.
- [ ] `ManajemenTokoController@setujui`: syarat min 3 dokumen valid.
- [ ] View pengajuan: teks `minimal 1` → `minimal 3`, counter, validasi JS,
      samakan enum status dokumen.

## A2 — Lock edit saat pending

- [ ] Blade pengajuan: disable input file + field saat status `pending`.
- [ ] Gate di `store`: tolak jika `pending` (hanya `null/ditolak` boleh).
- [ ] `DataTokoController@update`: block saat toko `pending`.

## A3 — Merah saat ditolak

- [ ] Tombol `Ajukan Ulang` → `bg-error text-white`.
- [ ] Badge hero data-toko: `ditolak/nonaktif` → merah (sekarang kuning).

## A4 — Upload ulang pasca-setuju

- [ ] Endpoint re-upload dokumen saat toko `aktif`: dokumen jadi `pending`
      + notif SuperAdmin (tanpa ubah status toko).

## A5 — Update data toko menunggu verifikasi

- [ ] `DataTokoController@update`: perubahan masuk antrean `pending`,
      notif SuperAdmin, form terkunci, `ActivityLogger`.
- [ ] Perbaiki teks misleading (klaim verifikasi palsu).

## Verifikasi

- [x] `php -l` + `view:cache` + `migrate`.
- [ ] Manual: ajukan 2 file (ditolak), 3 file (lolos), edit saat pending
      (terkunci), tolak (merah), re-upload aktif, update data toko (pending).

## Revisi susulan — state machine per dokumen

- Wajib trio KTP + NPWP + SIU; foto depan opsional.
- Terverifikasi terkunci (tidak bisa upload 2x); ditolak bisa upload +
  alasan penolakan bold; pending input disable.
- Re-upload aktif hanya untuk dokumen ditolak/hilang.
- `ManajemenToko@setujui` tetap min 3 valid.
