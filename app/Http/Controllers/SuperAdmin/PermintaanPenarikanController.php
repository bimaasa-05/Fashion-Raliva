<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PermintaanPenarikanController extends Controller
{
    public function index(Request $request)
    {
        $base = Withdrawal::query()
            ->with(['store.owner:user_id,nama_lengkap', 'wallet', 'bankAccount.bank', 'bank']);

        $stats = [
            'semua' => (clone $base)->count(),
            'pending' => (clone $base)->where('status', Withdrawal::STATUS_PENDING)->count(),
            'nominal_menunggu' => (float) (clone $base)->where('status', Withdrawal::STATUS_PENDING)->sum('jumlah'),
            'disetujui' => (clone $base)->where('status', Withdrawal::STATUS_DISETUJUI)->count(),
            'dibayar' => (clone $base)->where('status', Withdrawal::STATUS_DIBAYAR)->count(),
            'ditolak' => (clone $base)->where('status', Withdrawal::STATUS_DITOLAK)->count(),
            'total_semua' => (float) $withdrawals->whereIn('status', [Withdrawal::STATUS_DISETUJUI, Withdrawal::STATUS_DIBAYAR])->sum('jumlah'),
        ];

        $withdrawals = $base
            ->orderByDesc('diajukan_pada')
            ->paginate(15)
            ->withQueryString();

        return view('SuperAdmin.permintaan-penarikan.index', [
            'withdrawals' => $withdrawals,
            'stats' => $stats,
        ]);
    }

    public function setujui(Request $request, Withdrawal $penarikan)
    {
        if ($penarikan->status !== Withdrawal::STATUS_PENDING) {
            return back()->with('toast', [
                'message' => __('Hanya pengajuan berstatus menunggu yang dapat disetujui.'),
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'file_bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'deskripsi_bukti' => ['nullable', 'string', 'max:1000'],
        ], [
            'file_bukti.required' => __('Bukti transfer wajib dilampirkan.'),
            'file_bukti.mimes' => __('Bukti transfer harus berupa JPG, PNG, atau PDF.'),
            'file_bukti.max' => __('Ukuran bukti transfer maksimal 5 MB.'),
            'deskripsi_bukti.max' => __('Deskripsi bukti maksimal 1000 karakter.'),
        ]);

        $path = $request->file('file_bukti')->store('bukti-penarikan/'.$penarikan->withdrawal_id, 'public');

        $lama = $penarikan->only(['status', 'reviewed_by']);

        try {
            DB::transaction(function () use ($penarikan, $path, $data) {
                $locked = Withdrawal::whereKey($penarikan->withdrawal_id)->lockForUpdate()->firstOrFail();
                if ($locked->status !== Withdrawal::STATUS_PENDING) {
                    throw new \RuntimeException(__('Penarikan ini sudah diproses pihak lain.'));
                }

                $wallet = $locked->wallet()->lockForUpdate()->firstOrFail();

                if ((float) $wallet->saldo_tersedia < (float) $locked->jumlah) {
                    throw new \RuntimeException(__('Saldo tersedia tidak mencukupi untuk pencairan ini.'));
                }

                // Tahan dana: tersedia -> tertahan. Baris transaksi withdrawal
                // ditulis sekali saat tandaiDibayar agar laporan tidak ganda.
                $wallet->decrement('saldo_tersedia', $locked->jumlah);
                $wallet->increment('saldo_tertahan', $locked->jumlah);

                $locked->update([
                    'status' => Withdrawal::STATUS_DISETUJUI,
                    'reviewed_by' => ActivityLogger::resolveActorId(),
                    'ditinjau_pada' => now(),
                    'file_bukti' => $path,
                    'deskripsi_bukti' => $data['deskripsi_bukti'] ?? null,
                    'bukti_diupload_pada' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            Storage::disk('public')->delete($path);

            return back()->with('toast', [
                'message' => $e->getMessage(),
                'icon' => 'gpp_maybe',
            ]);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($path);
            throw $e;
        }

        ActivityLogger::log(
            'withdrawal.approve',
            Withdrawal::class,
            $penarikan->withdrawal_id,
            $lama,
            ['status' => Withdrawal::STATUS_DISETUJUI, 'file_bukti' => $path],
            sprintf('Menyetujui pencairan Rp %s untuk toko "%s"; dana ditahan menunggu pembayaran.', number_format((float) $penarikan->jumlah, 0, ',', '.'), $penarikan->store->nama_toko ?? '-')
        );

        if ($penarikan->store) {
            Notification::create([
                'user_id' => $penarikan->store->owner_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_WALLET,
                'judul' => __('Pencairan Disetujui'),
                'pesan' => sprintf(__('Dana pencairan sebesar Rp %s telah disetujui dan ditahan. Pembayaran segera dikirim ke rekening Anda.'), number_format((float) $penarikan->jumlah, 0, ',', '.')),
                'url' => route('owner.pencairan-dana'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_WALLET, __('Pencairan Disetujui'), sprintf(__('Pencairan Rp %s disetujui; dana ditahan.'), number_format((float) $penarikan->jumlah, 0, ',', '.')), route('superadmin.permintaan-penarikan'));

        return back()->with('toast', [
            'message' => sprintf(__('Pencairan Rp %s disetujui; dana ditahan menunggu pembayaran.'), number_format((float) $penarikan->jumlah, 0, ',', '.')),
            'icon' => 'task_alt',
        ]);
    }

    public function tolak(Request $request, Withdrawal $penarikan)
    {
        if (! in_array($penarikan->status, [Withdrawal::STATUS_PENDING, Withdrawal::STATUS_DISETUJUI], true)) {
            return back()->with('toast', [
                'message' => __('Hanya pengajuan berstatus menunggu atau disetujui yang dapat ditolak.'),
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $lama = $penarikan->only(['status', 'reviewed_by']);

        try {
            DB::transaction(function () use ($penarikan, $data) {
                $locked = Withdrawal::whereKey($penarikan->withdrawal_id)->lockForUpdate()->firstOrFail();
                if (! in_array($locked->status, [Withdrawal::STATUS_PENDING, Withdrawal::STATUS_DISETUJUI], true)) {
                    throw new \RuntimeException(__('Penarikan ini sudah diproses pihak lain.'));
                }

                // Kembalikan dana yang sempat ditahan saat persetujuan.
                if ($locked->status === Withdrawal::STATUS_DISETUJUI) {
                    $wallet = $locked->wallet()->lockForUpdate()->firstOrFail();
                    $wallet->increment('saldo_tersedia', $locked->jumlah);
                    $wallet->decrement('saldo_tertahan', $locked->jumlah);
                }

                $locked->update([
                    'status' => Withdrawal::STATUS_DITOLAK,
                    'reviewed_by' => ActivityLogger::resolveActorId(),
                    'ditinjau_pada' => now(),
                    'alasan_penolakan' => $data['alasan'],
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('toast', [
                'message' => $e->getMessage(),
                'icon' => 'gpp_maybe',
            ]);
        }

        ActivityLogger::log(
            'withdrawal.reject',
            Withdrawal::class,
            $penarikan->withdrawal_id,
            $lama,
            ['status' => Withdrawal::STATUS_DITOLAK, 'alasan_penolakan' => $data['alasan']],
            sprintf('Menolak pencairan toko "%s" dengan alasan: %s', $penarikan->store->nama_toko ?? '-', $data['alasan'])
        );

        if ($penarikan->store) {
            Notification::create([
                'user_id' => $penarikan->store->owner_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_WALLET,
                'judul' => 'Pencairan Ditolak',
                'pesan' => sprintf('Pengajuan pencairan Rp %s ditolak. Alasan: %s', number_format((float) $penarikan->jumlah, 0, ',', '.'), $data['alasan']),
                'url' => route('owner.pencairan-dana'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_WALLET, 'Pencairan Ditolak', sprintf('Pencairan Rp %s ditolak.', number_format((float) $penarikan->jumlah, 0, ',', '.')), route('superadmin.permintaan-penarikan'));

        return back()->with('toast', [
            'message' => __('Pengajuan pencairan ditolak.'),
            'icon' => 'block',
        ]);
    }

    public function tandaiDibayar(Request $request, Withdrawal $penarikan)
    {
        if ($penarikan->status !== Withdrawal::STATUS_DISETUJUI) {
            return back()->with('toast', [
                'message' => __('Hanya pencairan berstatus disetujui yang dapat ditandai dibayar.'),
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'file_bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'deskripsi_bukti' => ['nullable', 'string', 'max:1000'],
        ], [
            'file_bukti.required' => __('Bukti transfer wajib dilampirkan.'),
            'file_bukti.mimes' => __('Bukti transfer harus berupa JPG, PNG, atau PDF.'),
            'file_bukti.max' => __('Ukuran bukti transfer maksimal 5 MB.'),
            'deskripsi_bukti.max' => __('Deskripsi bukti maksimal 1000 karakter.'),
        ]);

        $path = $request->file('file_bukti')->store(
            'bukti-penarikan/'.$penarikan->withdrawal_id,
            'public'
        );

        $previousFile = $penarikan->file_bukti;

        try {
            DB::transaction(function () use ($penarikan, $path, $data) {
                $locked = Withdrawal::whereKey($penarikan->withdrawal_id)->lockForUpdate()->firstOrFail();
                if ($locked->status !== Withdrawal::STATUS_DISETUJUI) {
                    throw new \RuntimeException(__('Penarikan ini sudah diproses pihak lain.'));
                }

                $wallet = $locked->wallet()->lockForUpdate()->firstOrFail();
                $saldoSebelum = (float) $wallet->saldo_tertahan;

                $wallet->decrement('saldo_tertahan', $locked->jumlah);

                WalletTransaction::create([
                    'wallet_id' => $wallet->wallet_id,
                    'withdrawal_id' => $locked->withdrawal_id,
                    'jenis_transaksi' => WalletTransaction::JENIS_WITHDRAWAL,
                    'jumlah' => $locked->jumlah,
                    'saldo_sebelum' => $saldoSebelum,
                    'saldo_sesudah' => $saldoSebelum - (float) $locked->jumlah,
                    'keterangan' => sprintf('Pencairan dana ke %s (%s).', $locked->tujuan_nomor ?: '-', $locked->tujuan_penyedia ?: '-'),
                ]);

                $locked->update([
                    'status' => Withdrawal::STATUS_DIBAYAR,
                    'dibayar_pada' => now(),
                    'file_bukti' => $path,
                    'deskripsi_bukti' => $data['deskripsi_bukti'] ?? null,
                    'bukti_diupload_pada' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            Storage::disk('public')->delete($path);

            return back()->with('toast', [
                'message' => $e->getMessage(),
                'icon' => 'gpp_maybe',
            ]);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($path);
            throw $e;
        }

        if ($previousFile && $previousFile !== $path) {
            Storage::disk('public')->delete($previousFile);
        }

        ActivityLogger::log(
            'withdrawal.paid',
            Withdrawal::class,
            $penarikan->withdrawal_id,
            ['status' => Withdrawal::STATUS_DISETUJUI],
            ['status' => Withdrawal::STATUS_DIBAYAR],
            sprintf('Menandai pencairan Rp %s untuk toko "%s" sebagai dibayar.', number_format((float) $penarikan->jumlah, 0, ',', '.'), $penarikan->store->nama_toko ?? '-')
        );

        if ($penarikan->store) {
            Notification::create([
                'user_id' => $penarikan->store->owner_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_WALLET,
                'judul' => 'Pencairan Dibayar',
                'pesan' => sprintf('Dana pencairan sebesar Rp %s telah dikirim ke rekening Anda.', number_format((float) $penarikan->jumlah, 0, ',', '.')),
                'url' => route('owner.pencairan-dana'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_WALLET, 'Pencairan Dibayar', sprintf('Pencairan Rp %s ditandai dibayar.', number_format((float) $penarikan->jumlah, 0, ',', '.')), route('superadmin.permintaan-penarikan'));

        return back()->with('toast', [
            'message' => sprintf('Pencairan Rp %s ditandai sudah dibayar.', number_format((float) $penarikan->jumlah, 0, ',', '.')),
            'icon' => 'task_alt',
        ]);
    }
}
