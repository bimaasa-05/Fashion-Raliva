<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;

class CekResiController extends Controller
{
    public function index(Request $request)
    {
        $result = null;
        $error = null;
        $query = [
            'nomor_resi' => trim((string) $request->query('nomor_resi', '')),
            'nomor_order' => trim((string) $request->query('nomor_order', '')),
            'nomor_telepon' => trim((string) $request->query('nomor_telepon', '')),
        ];

        if ($query['nomor_resi'] !== '' || $query['nomor_order'] !== '') {
            [$result, $error] = $this->resolve($query);
        }

        return view('customer.cek-resi.index', [
            'result' => $result,
            'error' => $error,
            'query' => $query,
        ]);
    }

    public function search(Request $request)
    {
        $data = $request->validate([
            'nomor_resi' => 'nullable|string|max:100',
            'nomor_order' => 'nullable|string|max:100',
            'nomor_telepon' => 'nullable|string|max:30',
        ]);

        $nomorResi = trim((string) ($data['nomor_resi'] ?? ''));
        $nomorOrder = trim((string) ($data['nomor_order'] ?? ''));
        $nomorTelepon = trim((string) ($data['nomor_telepon'] ?? ''));

        if ($nomorResi === '' && $nomorOrder === '') {
            return back()->withErrors(['nomor_resi' => 'Isi nomor resi atau nomor order.'])->withInput();
        }
        if ($nomorOrder !== '' && $nomorTelepon === '') {
            return back()->withErrors(['nomor_telepon' => 'Nomor telepon wajib diisi saat cek via nomor order.'])->withInput();
        }

        // Redirect to GET for bookmarkable URL
        $params = [];
        if ($nomorResi !== '') $params['nomor_resi'] = $nomorResi;
        else {
            $params['nomor_order'] = $nomorOrder;
            $params['nomor_telepon'] = $nomorTelepon;
        }

        return redirect()->route('customer.cek-resi', $params);
    }

    /**
     * @return array{0: ?array, 1: ?string}
     */
    private function resolve(array $query): array
    {
        $nomorResi = $query['nomor_resi'] ?? '';
        $nomorOrder = $query['nomor_order'] ?? '';
        $nomorTelepon = $query['nomor_telepon'] ?? '';

        // I) via nomor resi
        if ($nomorResi !== '') {
            $shipment = Shipment::where('nomor_resi', $nomorResi)
                ->with(['order.checkout', 'order.store:store_id,nama_toko', 'order.items.productVariant.product.images', 'courier', 'shippingService', 'order.checkout.user:user_id,nama_lengkap,nomor_telepon'])
                ->first();

            if (! $shipment || ! $shipment->order) {
                return [null, 'Nomor resi tidak ditemukan.'];
            }

            $order = $shipment->order;
            $order->loadMissing(['items.productVariant.product.images', 'checkout', 'store']);

            return [[
                'mode' => 'resi',
                'shipment' => $shipment,
                'order' => $order,
                'timeline' => $this->timelineFor($order, $shipment),
            ], null];
        }

        // II) via nomor order + telepon
        $order = Order::where('nomor_order', $nomorOrder)
            ->with(['checkout.user:user_id,nama_lengkap,nomor_telepon', 'checkout', 'store:store_id,nama_toko', 'items.productVariant.product.images', 'shipments.courier', 'shipments.shippingService'])
            ->first();

        if (! $order) {
            return [null, 'Nomor order tidak ditemukan.'];
        }

        $checkout = $order->checkout;
        $user = $checkout?->user;
        $phones = array_filter([
            $checkout?->nomor_telepon,
            $checkout?->email_pelanggan, // just in case they input phone as email? not needed but keep
            $user?->nomor_telepon,
        ]);

        // Normalize phone compare: digits only
        $normInput = preg_replace('/\D+/', '', $nomorTelepon);
        $matched = false;
        foreach ($phones as $p) {
            $norm = preg_replace('/\D+/', '', (string) $p);
            if ($norm !== '' && $norm === $normInput) {
                $matched = true; break;
            }
            // also allow raw string match (for email fallback though we have phone)
            if (trim((string)$p) !== '' && trim((string)$p) === $nomorTelepon) {
                $matched = true; break;
            }
        }
        // Also allow email match if they typed email in telepon field (flexible)
        if (! $matched && $checkout?->email_pelanggan && trim((string)$checkout->email_pelanggan) === $nomorTelepon) {
            $matched = true;
        }

        if (! $matched) {
            return [null, 'Nomor order tidak cocok dengan nomor telepon. Periksa kembali.'];
        }

        $shipment = $order->shipments?->first();

        return [[
            'mode' => 'order',
            'shipment' => $shipment,
            'order' => $order,
            'timeline' => $this->timelineFor($order, $shipment),
        ], null];
    }

    private function timelineFor(Order $order, ?Shipment $shipment): array
    {
        // Steps: 1 Pending/Diproses awal, 2 Dikemas/Diproses, 3 Dikirim, 4 Diterima
        $steps = [
            ['key' => 'pending', 'label' => 'Pending'],
            ['key' => 'diproses', 'label' => 'Diproses'],
            ['key' => 'dikirim', 'label' => 'Dikirim'],
            ['key' => 'diterima', 'label' => 'Diterima'],
        ];

        $active = 1;

        if ($shipment) {
            $map = [
                Shipment::STATUS_PENDING => 1,
                Shipment::STATUS_DIPROSES => 2,
                Shipment::STATUS_DIKIRIM => 3,
                Shipment::STATUS_DITERIMA => 4,
                Shipment::STATUS_GAGAL => 0,
            ];
            $active = $map[$shipment->status] ?? 1;
        } else {
            $map = [
                Order::STATUS_PENDING_PAYMENT => 0,
                Order::STATUS_DIBAYAR => 1,
                Order::STATUS_DIPROSES => 2,
                Order::STATUS_DIKIRIM => 3,
                Order::STATUS_SELESAI => 4,
                Order::STATUS_DIBATALKAN => 0,
                Order::STATUS_REFUND => 0,
            ];
            $active = $map[$order->status] ?? 1;
        }

        return [
            'steps' => $steps,
            'active' => $active,
            'isGagal' => ($shipment?->status === Shipment::STATUS_GAGAL) || in_array($order->status, [Order::STATUS_DIBATALKAN, Order::STATUS_REFUND], true),
        ];
    }
}
