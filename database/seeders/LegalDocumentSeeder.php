<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class LegalDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $dokumen = [
            Setting::SYARAT_KETENTUAN => <<<'TEXT'
SYARAT & KETENTUAN PENGGUNAAN
PLATFORM RALIVA

Terakhir diperbarui: 1 Januari 2026

Dengan mengakses atau menggunakan platform Raliva, Anda dianggap telah membaca, memahami, dan menyetujui seluruh ketentuan yang tercantum di bawah ini.

1. Ketentuan Umum
1.1 Raliva adalah platform marketplace fesyen yang mempertemukan penjual (Pemilik Toko) dengan pembeli (Pelanggan).
1.2 Syarat & Ketentuan ini berlaku untuk seluruh pengguna platform, baik Pelanggan, Pemilik Toko, maupun pengguna lainnya.

2. Akun dan Tanggung Jawab Pengguna
2.1 Anda wajib memberikan data yang benar, lengkap, dan mutakhir saat pendaftaran.
2.2 Akun bersifat pribadi dan tidak boleh dipindahtangankan kepada pihak lain.
2.3 Anda bertanggung jawab penuh atas segala aktivitas yang terjadi pada akun Anda, termasuk kerahasiaan kata sandi.

3. Transaksi dan Pembayaran
3.1 Seluruh transaksi yang terjadi di platform diatur dalam kesepakatan antara Pembeli dan Penjual.
3.2 Pembayaran dilakukan melalui metode yang disediakan platform dan wajib diselesaikan sesuai ketentuan yang berlaku.
3.3 Komisi dan biaya layanan akan dikenakan sesuai pengaturan yang berlaku pada platform.

4. Pesanan, Pengiriman, dan Pengembalian
4.1 Penjual wajib memproses pesanan sesuai estimasi waktu yang ditampilkan di platform.
4.2 Pengembalian barang hanya dapat dilakukan sesuai kebijakan pengembalian yang berlaku.
4.3 Status dan riwayat pesanan dapat dipantau melalui halaman pesanan pada akun Anda.

5. Perilaku Dilarang
5.1 Memposting konten yang melanggar hukum, melanggar hak cipta, atau mengandung unsur SARA.
5.2 Melakukan penipuan, pemalsuan, atau tindakan lain yang merugikan pihak lain.
5.3 Memanfaatkan celah teknis platform untuk memperoleh keuntungan yang tidak sah.

6. Hak Kekayaan Intelektual
6.1 Seluruh konten, logo, dan merek pada platform adalah milik Raliva atau pemiliknya masing-masing.
6.2 Pengguna tidak diperkenankan menyalin, menggandakan, atau mendistribusikan konten platform tanpa izin tertulis.

7. Tanggung Jawab Platform
7.1 Raliva hanya menyediakan sarana transaksi dan tidak menjadi pihak dalam transaksi antara Pembeli dan Penjual.
7.2 Raliva akan berusaha menjaga keamanan dan ketersediaan layanan, namun tidak bertanggung jawab atas gangguan di luar kendali kami.

8. Perubahan Ketentuan
8.1 Raliva dapat memperbarui Syarat & Ketentuan ini sewaktu-waktu.
8.2 Perubahan akan diumumkan melalui platform dan berlaku efektif sejak diumumkan.

9. Penutup
Dengan menggunakan platform Raliva, Anda dianggap telah menyetujui seluruh ketentuan di atas. Untuk pertanyaan lebih lanjut, hubungi kami melalui layanan dukungan yang tersedia pada platform.
TEXT,
            Setting::KEBIJAKAN_PRIVASI => <<<'TEXT'
KEBIJAKAN PRIVASI
PLATFORM RALIVA

Terakhir diperbarui: 1 Januari 2026

Raliva menghormati dan melindungi data pribadi Anda. Kebijakan Privasi ini menjelaskan data yang kami kumpulkan, cara kami menggunakannya, serta hak yang Anda miliki.

1. Data yang Kami Kumpulkan
1.1 Data pendaftaran, seperti nama lengkap, alamat email, nomor telepon, dan alamat pengiriman.
1.2 Data transaksi, seperti riwayat pembelian, pembayaran, serta interaksi Anda dengan toko.
1.3 Data teknis, seperti alamat IP, jenis perangkat, dan aktivitas penggunaan platform.

2. Penggunaan Data
2.1 Memproses dan mengelola akun serta transaksi Anda.
2.2 Mengirimkan informasi terkait layanan, promo, dan pembaruan platform.
2.3 Meningkatkan kualitas layanan, keamanan, dan pengalaman pengguna.
2.4 Memenuhi kewajiban hukum dan peraturan yang berlaku.

3. Pembagian Data
3.1 Data Anda digunakan untuk keperluan penyelenggaraan platform dan tidak dijual kepada pihak ketiga.
3.2 Data yang diperlukan untuk proses pengiriman dapat dibagikan kepada kurir dan mitra logistik terkait.
3.3 Kami dapat membagikan data apabila diwajibkan oleh hukum atau peraturan yang berlaku.

4. Keamanan Data
4.1 Kami menerapkan langkah keamanan teknis dan organisasional untuk melindungi data Anda.
4.2 Meskipun tidak ada metode penyimpanan atau transmisi yang sepenuhnya aman, kami senantiasa menjaga data Anda dengan standar terbaik.

5. Hak Anda
5.1 Anda berhak mengakses, memperbarui, atau menghapus data pribadi Anda.
5.2 Anda dapat memilih untuk tidak menerima komunikasi promosi kapan saja melalui pengaturan akun.

6. Perubahan Kebijakan
Kebijakan Privasi ini dapat diperbarui sewaktu-waktu. Perubahan akan diumumkan melalui platform dan berlaku efektif sejak diumumkan.

7. Hubungi Kami
Apabila Anda memiliki pertanyaan mengenai Kebijakan Privasi ini, silakan hubungi layanan dukungan Raliva melalui kanal yang tersedia di platform.
TEXT,
        ];

        foreach ($dokumen as $kunci => $nilai) {
            if (Setting::get($kunci) === null) {
                Setting::set($kunci, $nilai);
            }
        }
    }
}