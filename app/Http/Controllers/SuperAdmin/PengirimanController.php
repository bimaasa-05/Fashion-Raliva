<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;

class PengirimanController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with([
            'order:order_id,nomor_order,store_id',
            'order.store:store_id,nama_toko',
            'courier:courier_id,nama_kurir',
            'shippingService:shipping_service_id,nama_layanan',
        ])->orderByDesc('shipments.created_at')->get();

        $stats = [
            'semua' => $shipments->count(),
            'pending' => $shipments->where('status', 'pending')->count(),
            'diproses' => $shipments->where('status', 'diproses')->count(),
            'dikirim' => $shipments->where('status', 'dikirim')->count(),
            'diterima' => $shipments->where('status', 'diterima')->count(),
            'gagal' => $shipments->where('status', 'gagal')->count(),
        ];

        return view('SuperAdmin.pengiriman.index', [
            'shipments' => $shipments,
            'stats' => $stats,
        ]);
    }
}
