Berikut langkah tes end-to-end dari checkout sampai refund selesai (pakai server http://127.0.0.1:8000; siapkan akun: c@gmail.com/123, admin@raliva.test/password, o@gmail.com/123, produksi@raliva.test/password). Saya sudah cek alur aslinya di route + controller, jadi URL dan titik cek di bawah akurat.


Fase 1 — Checkout & Bayar (Customer)
1. Login c@gmail.com/123 → /customer/shop → pilih produk → Beli Sekarang. Isi alamat + kurir → submit → lanjut /customer/checkout/{id}/payment.
2. Pilih metode bank/transfer/ewallet → pilih akun platform → upload bukti bayar, submit → status payment menunggu_verifikasi. (Alternatif cepat: bayar Saldo Akun → langsung dibayar.)
3. Catat no. order & grand_total.


Fase 2 — Verifikasi Pembayaran (Admin)
4. Login admin@raliva.test/password → /admin/verifikasi-pembayaran → Setujui → order jadi menunggu_produksi + log verifikasi_pembayaran.


Fase 3 — Produksi → Dikirim → Selesai
5. produksi@raliva.test/password → /produksi/data-produksi → Accept → progres produksi → QC /produksi/pemeriksaan-kualitas → order siap_kirim.
6. Admin → /admin/pengiriman → simpan resi (shipment diproses) → kirim → order dikirim.
7. Customer → /customer/order-tracking?order=… → Terima Pesanan (confirm) → order selesai, dana penjualan dikredit ke wallet owner (WalletService::creditOrder), shipment diterima.
- Shortcut: data seed sudah punya order dikirim/selesai ber-payment (mis. order #21) — langkah 5–7 bisa dilewati.


Fase 4 — Komplain & Ajukan Refund (Customer)
8. Customer → /customer/komplain → Buat Komplain (pilih order di atas; hanya order dikirim/selesai yang muncul) → kirim pesan → status komplain open.
9. Buka thread → menu "Ajukan Refund" → modal: tipe Penuh, jumlah = grand_total, alasan ≥20 karkar, bukti gambar jpg/png ≤4MB → submit.
- Cek: refund requested, badge Requested, file di storage/app/public/bukti-refund-request/{order_id}/.
- Negatif: komplain order lain → tab/tombol tidak muncul; jumlah > grand_total ditolak; file >4MB ditolak; pengajuan kedua saat masih ada refund aktif ditolak.


Fase 5 — Review & Selesaikan Refund (Admin + Owner) — inti perubahan batch ini
10. Admin → /admin/pengembalian-dana → refund tampil (scope toko) → Setujui → status disetujui. (Owner boleh setujui/tolak hanya untuk refund escalated — tidak menimpa reviewed_by admin.)
11. Owner → /owner/keuangan → catat saldo sebelum. Buka /owner/pengembalian-dana → seksi Menunggu Penyelesaian Anda → Selesaikan → upload bukti (selesaikan wajib file, jpg/jpeg/png/pdf ≤5MB).
- Cek hasil: status refund selesai; wallet toko terpotong = jumlah; WalletTransaction REFUND_KELUAR; file di storage/app/public/bukti-refund/{id}/; log refund.complete.
12. Flip otomatis: karena tipe full & jumlah = grand_total & order selesai → order otomatis jadi refund + log order.refunded; di /owner/pesanan masuk tab/chip Refund; customer melihat status timeline Refund. (Refund partial → order tetap di status semula.)
- Negatif: Selesaikan ulang refund yang sama → ditolak "status sudah berubah"; tanpa file → ValidationException; jumlah > saldo toko → ditolak & file bukti dihapus.


Fase 6 — Verifikasi Akhir (Saldo & Laporan)
13. Customer → /customer/saldo: saldo akun naik = jumlah refund (kredit KODE_SALDO_AKUN); /customer/order-tracking menunjukkan status Refund.
14. Owner → /owner/laporan: Total Pendapatan tidak turun (R1 whereIn([selesai,refund])), baris Refund naik, count Pesanan Selesai turun. /owner/rekap-karyawan: kolom Refund naik = jumlah (hanya refund selesai).
15. Admin → /admin/laporan & /admin/riwayat-aktivitas; Super Admin → /superadmin/pengembalian-dana + .laporan (sama-sama Satu Jalur: SA juga memotong wallet toko saat selesaikan).
Catatan penting: order harus sampai status dikirim/selesai dulu baru bisa refund (guard di storeRefund), dan flip refund baru terjadi saat diselesaikan penuh — bukan saat customer mengajukan.