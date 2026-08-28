<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;

class PengirimanController extends Controller
{
    public function index()
    {
        $query = Shipment::with([
            'order:order_id,nomor_order,store_id',
            'order.store:store_id,nama_toko',
            'courier: courier_id,nama_kurir',
            'shippingService: shipping_service_id,nama_layanan',
        ])->orderByDesc('shipments.created_at');

        if ($status = request('status')) {
            $query->where('shipments.status', $status);
        }

        $shipments = $query->paginate(20)->withQueryString();

        return view('SuperAdmin.pengiriman.index', [
            'shipments' => $shipments,
        ]);
    }
}
