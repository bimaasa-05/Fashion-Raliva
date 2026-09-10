<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\Role;
use App\Models\Store;
use App\Models\StoreDocument;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Bahan uji halaman Pengajuan Toko untuk semua kondisi.
 *
 * Dijalankan manual (tidak terdaftar di DatabaseSeeder):
 *   php artisan db:seed --class=PengajuanTokoTestSeeder
 *
 * Idempotent — aman dijalankan ulang. Password semua akun: "password".
 *
 *   owner.baru@raliva.test    → tanpa store (uji form "Ajukan Toko Baru" dari NOL)
 *   owner.pending@raliva.test → store pending + 2 dokumen (uji form "Lengkapi Dokumen" + progres tahap 1-2)
 *   owner.ditolak@raliva.test → store ditolak + alasan (uji form repair + progres merah tahap 3)
 *   (akun aktif existing, mis. owner utama → uji tampilan terkunci + 4 tahap centang)
 */
class PengajuanTokoTestSeeder extends Seeder
{
    public function run(): void
    {
        $ownerRoleId = Role::where('nama_role', Role::OWNER)->value('role_id');

        if (! $ownerRoleId) {
            $this->command->error('Role Owner tidak ditemukan. Batal.');

            return;
        }

        $baru = $this->owner('Owner Baru Test', 'owner.baru@raliva.test', $ownerRoleId);
        $pending = $this->owner('Owner Pending Test', 'owner.pending@raliva.test', $ownerRoleId);
        $ditolak = $this->owner('Owner Ditolak Test', 'owner.ditolak@raliva.test', $ownerRoleId);

        // Kondisi 2: store pending + 2 dokumen (sisakan 2 jenis kosong untuk uji tambah).
        $pendingStore = Store::firstOrCreate(
            ['owner_id' => $pending->user_id],
            [
                'nama_toko' => 'Toko Pending Test',
                'kategori' => 'Fashion & Lifestyle',
                'alamat' => 'Jl. Test Pending No. 1, Bandung',
                'nomor_telepon' => '081200000101',
                'deskripsi' => 'Toko bahan uji kondisi pending.',
                'status' => Store::STATUS_PENDING,
            ]
        );
        $this->doc($pendingStore->store_id, 'ktp', 'pending');
        $this->doc($pendingStore->store_id, 'npwp', 'pending');
        $this->notif(
            $pending->user_id,
            Notification::TIPE_SISTEM,
            'Pengajuan Diproses',
            'Pengajuan toko "Toko Pending Test" sedang menunggu verifikasi Super Admin.',
            route('owner.pengajuan-toko')
        );

        // Kondisi 3: store ditolak + alasan + 1 dokumen ditolak bercatatan.
        $ditolakStore = Store::firstOrCreate(
            ['owner_id' => $ditolak->user_id],
            [
                'nama_toko' => 'Toko Ditolak Test',
                'kategori' => 'Pakaian Wanita',
                'alamat' => 'Jl. Test Ditolak No. 2, Jakarta',
                'nomor_telepon' => '081200000102',
                'deskripsi' => 'Toko bahan uji kondisi ditolak.',
                'status' => Store::STATUS_DITOLAK,
                'alasan_penolakan' => 'Foto depan toko tidak jelas, mohon unggah ulang dengan resolusi lebih tinggi.',
            ]
        );
        $this->doc($ditolakStore->store_id, 'ktp', 'ditolak', 'File buram, tidak terbaca.');
        $this->doc($ditolakStore->store_id, 'npwp', 'pending');
        $this->notif(
            $ditolak->user_id,
            Notification::TIPE_SISTEM,
            'Pengajuan Ditolak',
            'Pengajuan toko "Toko Ditolak Test" ditolak. Periksa alasan dan ajukan ulang.',
            route('owner.pengajuan-toko')
        );

        $this->command->info('Bahan uji pengajuan toko siap (password semua: password):');
        $this->command->line(' - owner.baru@raliva.test    : tanpa store (form baru)');
        $this->command->line(' - owner.pending@raliva.test : pending + 2 dokumen (form lengkapi)');
        $this->command->line(' - owner.ditolak@raliva.test : ditolak + alasan (form repair)');
    }

    private function owner(string $nama, string $email, int $roleId): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'nama_lengkap' => $nama,
                'password' => Hash::make('password'),
                'role_id' => $roleId,
                'nomor_telepon' => '081200000100',
                'status' => User::STATUS_AKTIF,
            ]
        );
    }

    private function doc(int $storeId, string $jenis, string $status, ?string $catatan = null): void
    {
        StoreDocument::updateOrCreate(
            ['store_id' => $storeId, 'jenis' => $jenis],
            [
                'path' => 'store-documents/' . $storeId . '/' . $jenis . '.pdf',
                'status' => $status,
                'catatan' => $catatan,
            ]
        );
    }

    private function notif(int $userId, string $tipe, string $judul, string $pesan, ?string $url): void
    {
        Notification::firstOrCreate(
            ['user_id' => $userId, 'judul' => $judul],
            [
                'aktor_id' => $userId,
                'tipe' => $tipe,
                'pesan' => $pesan,
                'url' => $url,
            ]
        );
    }
}
