<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use App\Models\StoreExpense;
use App\Support\AdminContext;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $storeId = $storeIds[0] ?? null;

        // pendapatan = sum grand_total where status selesai for admin's stores
        $pendapatan = $storeId ? (float) Order::whereIn('store_id', $storeIds)->where('status', 'selesai')->sum('grand_total') : 0;
        $pesananDiproses = $storeId ? Order::whereIn('store_id', $storeIds)->where('status', 'selesai')->count() : 0;

        // pengeluaran = refund selesai + store expense
        $refund = $storeId ? (float) Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
            ->whereIn('orders.store_id', $storeIds)->where('refunds.status', 'selesai')->sum('refunds.jumlah') : 0;
        $expense = $storeId ? (float) StoreExpense::whereIn('store_id', $storeIds)->sum('nominal') : 0;
        $totalPengeluaran = $refund + $expense;
        $totalBersih = $pendapatan - $totalPengeluaran;

        // status counts for admin
        $pesananBaru = $storeId ? Order::whereIn('store_id', $storeIds)->whereIn('status', ['pending_payment','dibayar'])->count() : 0;
        $menungguVerifikasi = $storeId ? \App\Models\Payment::whereHas('checkout.order', fn($q)=>$q->whereIn('store_id',$storeIds))->where('status','menunggu_verifikasi')->count() : 0;

        // per toko breakdown for admin with multiple stores
        $perToko = collect();
        if ($storeIds) {
            $stores = \App\Models\Store::whereIn('store_id', $storeIds)->get();
            foreach ($stores as $s) {
                $p = (float) Order::where('store_id', $s->store_id)->where('status','selesai')->sum('grand_total');
                $exp = (float) StoreExpense::where('store_id', $s->store_id)->sum('nominal');
                $ref = (float) Refund::join('orders','orders.order_id','=','refunds.order_id')->where('orders.store_id',$s->store_id)->where('refunds.status','selesai')->sum('refunds.jumlah');
                $perToko->push((object)[
                    'nama_toko'=>$s->nama_toko,
                    'pesanan'=> Order::where('store_id',$s->store_id)->where('status','selesai')->count(),
                    'pendapatan'=>$p,
                    'pengeluaran'=>$exp+$ref,
                    'bersih'=>$p - ($exp+$ref),
                ]);
            }
        }

        return view('Admin.laporan.index', compact('pendapatan','pesananDiproses','totalPengeluaran','totalBersih','perToko','pesananBaru','menungguVerifikasi'));
    }
}