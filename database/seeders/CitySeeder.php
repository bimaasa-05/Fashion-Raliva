<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Master kota Indonesia per pulau. Idempotent — aman dijalankan ulang.
     */
    public function run(): void
    {
        $data = [
            'Sumatera' => [
                'Banda Aceh', 'Sabang', 'Lhokseumawe', 'Langsa', 'Subulussalam',
                'Medan', 'Binjai', 'Pematangsiantar', 'Sibolga', 'Tanjungbalai',
                'Tebing Tinggi', 'Padangsidimpuan', 'Gunungsitoli', 'Padang',
                'Solok', 'Sawahlunto', 'Padang Panjang', 'Bukittinggi',
                'Payakumbuh', 'Pariaman', 'Pekanbaru', 'Dumai', 'Jambi',
                'Sungai Penuh', 'Palembang', 'Pagar Alam', 'Lubuklinggau',
                'Prabumulih', 'Bengkulu', 'Bandar Lampung', 'Metro',
                'Pangkalpinang', 'Batam', 'Tanjungpinang',
            ],
            'Jawa' => [
                'Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan',
                'Jakarta Timur', 'Bogor', 'Sukabumi', 'Bandung', 'Cirebon', 'Indramayu',
                'Bekasi', 'Depok', 'Cimahi', 'Cianjur', 'Tasikmalaya', 'Banjar', 'Magelang',
                'Surakarta (Solo)', 'Salatiga', 'Semarang', 'Pekalongan', 'Tegal',
                'Yogyakarta', 'Kediri', 'Blitar', 'Malang', 'Probolinggo',
                'Pasuruan', 'Mojokerto', 'Madiun', 'Surabaya', 'Batu',
                'Tangerang', 'Cilegon', 'Serang', 'Tangerang Selatan',
            ],
            'Bali & Nusa Tenggara' => ['Denpasar', 'Mataram', 'Bima', 'Kupang'],
            'Kalimantan' => [
                'Pontianak', 'Singkawang', 'Palangka Raya', 'Banjarmasin',
                'Banjarbaru', 'Balikpapan', 'Samarinda', 'Bontang', 'Tarakan',
            ],
            'Sulawesi' => [
                'Manado', 'Bitung', 'Tomohon', 'Kotamobagu', 'Palu', 'Makassar',
                'Parepare', 'Palopo', 'Kendari', 'Baubau', 'Gorontalo',
            ],
            'Maluku & Papua' => ['Ambon', 'Tual', 'Ternate', 'Tidore Kepulauan', 'Jayapura', 'Sorong'],
        ];

        foreach ($data as $pulau => $kotas) {
            foreach ($kotas as $nama) {
                City::updateOrCreate(['nama_kota' => $nama], ['pulau' => $pulau]);
            }
        }
    }
}
