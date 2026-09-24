# Penjelasan Revisi: Dokumen Toko, Pengajuan & Verifikasi (Batch A + Susulan)

Dokumen ini menjelaskan alur **dokumen persyaratan toko** — dari pengajuan Owner,
verifikasi SuperAdmin, sampai perubahan data — mencakup kondisi setelah Batch A
dan perbaikan susulan state machine per-dokumen.

> Istilah: **Owner** = pemilik toko. **SA/SuperAdmin** = verifikator.
> Status toko: `pending / aktif / nonaktif / ditolak`.
> Status dokumen: `pending / terverifikasi / ditolak`.

---

## 1. Pengajuan toko baru — wajib trio, foto depan opsional

**Aturan:** KTP + NPWP + SIU wajib diunggah. Foto depan toko opsional.

```
Owner isi nama/alamat/telp + unggah KTP + NPWP + SIU (+ foto depan bila ada)
  │  validasi: ketiganya wajib ada (file baru)
  ▼
Store::create(status=pending) + StoreDocument per jenis (status=pending)
  │  notif ke SA + fireSelf ke Owner
  ▼
SA verifikasi per dokumen → setujui toko (syarat: ≥3 dokumen valid)
```

File lama di storage dihapus saat di-overwrite; pasangan `[store_id, jenis]`
unik di database sehingga tidak ada dokumen ganda.

## 2. State machine per dokumen (aturan kunci)

| Status dokumen | Bisa upload ulang? | Input di form |
|---|---|---|
| `pending` | Tidak (terkunci, menunggu SA) | disabled + kursor not-allowed |
| `terverifikasi` | **Tidak, walau dipaksa** (controller menolak) | tidak ada input, hanya gembok |
| `ditolak` | **Ya** (satu-satunya jalan) | input aktif + tombol merah |
| belum ada | Ya | input aktif |

Alasan penolakan tampil **tebal** (label merah + teks bold) agar Owner langsung
paham. Tombol submit (Ajukan Toko / Ajukan Ulang) mati sampai ada file dipilih.

## 3. Lock saat pending & Data Toko

- Toko `pending`: form pengajuan terkunci total (controller + blade).
- Halaman **Data Toko hanya terbuka untuk toko `aktif`** — selain itu redirect
  ke pengajuan + info. Update langsung diblokir non-aktif.

## 4. Upload ulang saat toko aktif

Toko tetap **aktif** selama verifikasi. Setiap dokumen yang kurang/ditolak
punya tombol **Ajukan File** eksplisit (aktif setelah file dipilih).
Dokumen yang diunggah ulang kembali `pending` + notif SA; SA memverifikasi
dari daftar dokumen yang sama.

## 5. Perubahan Data Toko menunggu verifikasi

```
Owner ubah nama/kategori/deskripsi/alamat/telp (+ email langsung tersimpan)
  │  dicek: tidak ada antrean pending lain + ada yang berubah
  ▼
store_update_requests (status=pending) + form dikunci + banner jadwal
  │  notif SA + ActivityLogger + fireSelf
  ▼
SA setujui → diterapkan ke stores ─── atau ─── tolak + alasan (modal SA)
```

## 6. Sisi SuperAdmin

- Setujui toko mensyaratkan **≥3 dokumen valid**; dokumen pending ikut
  terverifikasi otomatis.
- Klik kartu toko **aktif** membuka **detail dulu**; penangguhan hanya lewat
  tombol Tangguhkan di dalam detail (tidak langsung melompat).
- Modal perubahan data menampilkan lama vs baru yang berubah (highlight),
  dengan tombol Setujui / Tolak + alasan.

## 7. Catatan jujur (batasan sadar)

1. Toko `pending` lama yang dokumennya < 3 tidak bisa melengkapi sendiri
   (terkunci); jalurnya SA menolak → status `ditolak` → Owner revisi.
2. Email akun langsung tersimpan (bukan bagian verifikasi toko).
3. `suppliers.stok/jenis` dan kolom legacy lain dibiarkan di DB walau tak
   dipakai UI lagi.
