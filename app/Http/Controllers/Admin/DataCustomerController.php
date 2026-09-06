<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DataCustomerController extends Controller
{
    public function index()
    {
        $customers = User::withCount('orders')
            ->withSum('orders', 'grand_total')
            ->with(['orders' => fn ($q) => $q->latest()->limit(5), 'reviews' => fn ($q) => $q->latest()->limit(5)])
            ->where('role_id', Role::CUSTOMER)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('Admin.customer.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $roleId = Role::where('nama_role', Role::CUSTOMER)->value('role_id');

        if (! $roleId) {
            return back()->with('error', 'Role customer belum tersedia.');
        }

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer = User::create([
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => $data['email'],
            'nomor_telepon' => $data['nomor_telepon'] ?? null,
            'password' => Hash::make($data['password']),
            'role_id' => $roleId,
            'email_verified_at' => now(),
            'status' => User::STATUS_AKTIF,
        ]);

        return back()->with('success', 'Customer ' . $customer->nama_lengkap . ' berhasil ditambahkan.');
    }
}
