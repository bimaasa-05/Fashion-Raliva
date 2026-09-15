@extends('layouts.superadmin')

@section('title', 'Data Bank')

@section('header-title', 'Data Bank')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola rekening bank, e-wallet, dan QRIS platform')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .tab-btn.active { background: var(--chrome-accent); color: #fff; }
    .tab-btn { transition: all .2s; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Toolbar -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-gold-accent text-[20px]">account_balance</span>
            </div>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Data Bank</h2>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Kelola rekening bank, e-wallet, dan QRIS platform.</p>
            </div>
        </div>
    </section>

    <!-- Tabs -->
    <div class="flex gap-2 border-b border-[var(--border-soft)]">
        <button class="tab-btn active px-4 py-2 rounded-t-lg font-label-sm text-sm" data-tab="bank" onclick="switchTab('bank')">
            <span class="material-symbols-outlined text-[18px] align-middle">account_balance</span> Bank Transfer
        </button>
        <button class="tab-btn px-4 py-2 rounded-t-lg font-label-sm text-sm" data-tab="ewallet" onclick="switchTab('ewallet')">
            <span class="material-symbols-outlined text-[18px] align-middle">account_balance_wallet</span> E-Wallet
        </button>
        <button class="tab-btn px-4 py-2 rounded-t-lg font-label-sm text-sm" data-tab="qris" onclick="switchTab('qris')">
            <span class="material-symbols-outlined text-[18px] align-middle">qr_code_2</span> QRIS
        </button>
    </div>

    <!-- Panel: Bank Transfer -->
    <div id="panel-bank" class="tab-panel">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-title-md text-title-md text-on-surface">Bank Transfer</h3>
            <button type="button" onclick="openBankForm()" class="flex items-center gap-2 px-4 py-2 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">
                <span class="material-symbols-outlined text-[18px]">add</span> Tambah Bank
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-on-surface-variant border-b border-[var(--border-soft)]">
                        <th class="py-3 px-2">Bank</th>
                        <th class="py-3 px-2">Kode</th>
                        <th class="py-3 px-2">No. Rekening</th>
                        <th class="py-3 px-2">Pemilik</th>
                        <th class="py-3 px-2">Status</th>
                        <th class="py-3 px-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banks as $bank)
                        @php $rek = $bank->platformBankAccounts->first(); @endphp
                        <tr class="border-b border-[var(--border-soft)] hover:bg-surface-container-low/50">
                            <td class="py-3 px-2 font-medium">{{ $bank->nama_bank }}</td>
                            <td class="py-3 px-2">{{ $bank->kode_bank }}</td>
                            <td class="py-3 px-2">{{ $rek?->nomor_rekening ?? '-' }}</td>
                            <td class="py-3 px-2">{{ $rek?->nama_pemilik ?? '-' }}</td>
                            <td class="py-3 px-2">
                                <span class="px-2 py-0.5 rounded text-xs {{ $bank->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $bank->status }}</span>
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex gap-1">
                                    <button onclick="editBank({{ $bank->bank_id }})" class="text-blue-600 hover:text-blue-800" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                                    <form action="{{ route('superadmin.data-bank.hapus', $bank) }}" method="POST" onsubmit="return confirm('Hapus bank {{ $bank->nama_bank }}?')">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Panel: E-Wallet -->
    <div id="panel-ewallet" class="tab-panel hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-title-md text-title-md text-on-surface">E-Wallet</h3>
            <button type="button" onclick="openEwalletForm()" class="flex items-center gap-2 px-4 py-2 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">
                <span class="material-symbols-outlined text-[18px]">add</span> Tambah E-Wallet
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-on-surface-variant border-b border-[var(--border-soft)]">
                        <th class="py-3 px-2">Nama</th>
                        <th class="py-3 px-2">Kode</th>
                        <th class="py-3 px-2">No. Telepon</th>
                        <th class="py-3 px-2">Pemilik</th>
                        <th class="py-3 px-2">Status</th>
                        <th class="py-3 px-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ewallets as $ew)
                        <tr class="border-b border-[var(--border-soft)] hover:bg-surface-container-low/50">
                            <td class="py-3 px-2 font-medium">{{ $ew->nama }}</td>
                            <td class="py-3 px-2">{{ $ew->kode }}</td>
                            <td class="py-3 px-2">{{ $ew->nomor_rekening ?? '-' }}</td>
                            <td class="py-3 px-2">{{ $ew->nama_pemilik ?? '-' }}</td>
                            <td class="py-3 px-2">
                                <span class="px-2 py-0.5 rounded text-xs {{ $ew->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $ew->status }}</span>
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex gap-1">
                                    <button onclick="editEwallet({{ $ew->platform_bank_account_id }})" class="text-blue-600 hover:text-blue-800" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                                    <form action="{{ route('superadmin.data-bank.account.hapus', $ew) }}" method="POST" onsubmit="return confirm('Hapus e-wallet {{ $ew->nama }}?')">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Panel: QRIS -->
    <div id="panel-qris" class="tab-panel hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-title-md text-title-md text-on-surface">QRIS</h3>
            @if ($qris)
                <button type="button" onclick="editQris({{ $qris->platform_bank_account_id }})" class="flex items-center gap-2 px-4 py-2 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">
                    <span class="material-symbols-outlined text-[18px]">edit</span> Edit QRIS
                </button>
            @else
                <button type="button" onclick="openQrisForm()" class="flex items-center gap-2 px-4 py-2 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">
                    <span class="material-symbols-outlined text-[18px]">add</span> Tambah QRIS
                </button>
            @endif
        </div>
        @if ($qris)
            <div class="flex flex-col sm:flex-row gap-6 p-4 border border-[var(--border-soft)] rounded-xl">
                @if ($qris->file_gambar)
                    <img src="{{ asset('storage/' . ltrim($qris->file_gambar, '/')) }}" alt="{{ $qris->nama }}" class="w-48 h-48 object-contain rounded-lg border border-[var(--border-soft)] bg-white" />
                @endif
                <div class="flex-1 space-y-2">
                    <p class="font-title-md text-title-md text-on-surface">{{ $qris->nama }}</p>
                    <p class="text-on-surface-variant text-sm">{{ $qris->deskripsi }}</p>
                    <p class="text-sm">Nama: <strong>{{ $qris->nama_pemilik }}</strong></p>
                    <p class="text-sm">Status: <span class="px-2 py-0.5 rounded text-xs {{ $qris->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $qris->status }}</span></p>
                </div>
            </div>
        @else
            <p class="text-on-surface-variant text-sm">Belum ada akun QRIS. Klik "Tambah QRIS" untuk membuat.</p>
        @endif
    </div>
</div>

<!-- Modal: Bank Form -->
<div id="modal-bank" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-surface-container-high rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 id="bank-modal-title" class="font-title-md text-title-md text-on-surface">Tambah Bank</h3>
            <button onclick="closeBankForm()" class="text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="form-bank" method="POST" action="{{ route('superadmin.data-bank.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="POST" id="bank-method" />
            <input type="hidden" name="bank_id" id="bank-id" />
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Nama Bank</label>
                    <input type="text" name="nama_bank" id="bank-nama" required class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Kode Bank</label>
                    <input type="text" name="kode_bank" id="bank-kode" required class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Nomor Rekening</label>
                    <input type="text" name="nomor_rekening" id="bank-rekening" required class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Nama Pemilik</label>
                    <input type="text" name="nama_pemilik" id="bank-pemilik" required class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Status</label>
                    <select name="status" id="bank-status" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeBankForm()" class="px-4 py-2 text-sm rounded-lg border border-[var(--border-soft)] text-on-surface-variant">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-deep-onyx text-on-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: E-Wallet Form -->
<div id="modal-ewallet" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-surface-container-high rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 id="ewallet-modal-title" class="font-title-md text-title-md text-on-surface">Tambah E-Wallet</h3>
            <button onclick="closeEwalletForm()" class="text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="form-ewallet" method="POST" action="{{ route('superadmin.data-bank.account.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis" value="ewallet" />
            <input type="hidden" name="account_id" id="ewallet-id" />
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Nama</label>
                    <input type="text" name="nama" id="ewallet-nama" required class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Kode</label>
                    <input type="text" name="kode" id="ewallet-kode" required class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">No. Telepon</label>
                    <input type="text" name="nomor_rekening" id="ewallet-rekening" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Nama Pemilik</label>
                    <input type="text" name="nama_pemilik" id="ewallet-pemilik" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" value="RALIVA Fashion" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="ewallet-deskripsi" rows="2" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Logo</label>
                    <input type="file" name="file_gambar" accept="image/*" class="w-full text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Status</label>
                    <select name="status" id="ewallet-status" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeEwalletForm()" class="px-4 py-2 text-sm rounded-lg border border-[var(--border-soft)] text-on-surface-variant">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-deep-onyx text-on-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: QRIS Form -->
<div id="modal-qris" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-surface-container-high rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 id="qris-modal-title" class="font-title-md text-title-md text-on-surface">Tambah QRIS</h3>
            <button onclick="closeQrisForm()" class="text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="form-qris" method="POST" action="{{ route('superadmin.data-bank.account.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis" value="qris" />
            <input type="hidden" name="account_id" id="qris-id" />
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Nama</label>
                    <input type="text" name="nama" id="qris-nama" required class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" value="QRIS RALIVA" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Kode</label>
                    <input type="text" name="kode" id="qris-kode" required class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" value="qris" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Nama Pemilik</label>
                    <input type="text" name="nama_pemilik" id="qris-pemilik" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" value="RALIVA Fashion" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="qris-deskripsi" rows="2" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm">Scan kode QR dengan aplikasi apa pun (GoPay, OVO, Dana, ShopeePay, m-Banking).</textarea>
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Gambar QR</label>
                    <input type="file" name="file_gambar" accept="image/*" class="w-full text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-on-surface-variant mb-1">Status</label>
                    <select name="status" id="qris-status" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeQrisForm()" class="px-4 py-2 text-sm rounded-lg border border-[var(--border-soft)] text-on-surface-variant">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-deep-onyx text-on-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.getElementById('panel-' + tab).classList.remove('hidden');
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelector('[data-tab="' + tab + '"]').classList.add('active');
    }

    // Bank modal
    function openBankForm() {
        document.getElementById('bank-modal-title').textContent = 'Tambah Bank';
        document.getElementById('form-bank').action = '{{ route('superadmin.data-bank.store') }}';
        document.getElementById('bank-id').value = '';
        document.getElementById('bank-nama').value = '';
        document.getElementById('bank-kode').value = '';
        document.getElementById('bank-rekening').value = '';
        document.getElementById('bank-pemilik').value = '';
        document.getElementById('bank-status').value = 'aktif';
        document.getElementById('modal-bank').classList.remove('hidden');
        document.getElementById('modal-bank').classList.add('flex');
    }

    function closeBankForm() {
        document.getElementById('modal-bank').classList.add('hidden');
        document.getElementById('modal-bank').classList.remove('flex');
    }

    function editBank(id) {
        // fetch bank data via AJAX
        fetch(`/superadmin/data-bank/${id}/edit`)
            .then(r => r.json())
            .then(d => {
                document.getElementById('bank-modal-title').textContent = 'Edit Bank';
                document.getElementById('form-bank').action = '{{ route('superadmin.data-bank.update', ['bank' => ':ID']) }}'.replace(':ID', id);
                document.getElementById('bank-id').value = id;
                document.getElementById('bank-nama').value = d.nama_bank;
                document.getElementById('bank-kode').value = d.kode_bank;
                document.getElementById('bank-rekening').value = d.rekening || '';
                document.getElementById('bank-pemilik').value = d.pemilik || '';
                document.getElementById('bank-status').value = d.status;
                document.getElementById('modal-bank').classList.remove('hidden');
                document.getElementById('modal-bank').classList.add('flex');
            });
    }

    // E-Wallet modal
    function openEwalletForm() {
        document.getElementById('ewallet-modal-title').textContent = 'Tambah E-Wallet';
        document.getElementById('form-ewallet').action = '{{ route('superadmin.data-bank.account.store') }}';
        document.getElementById('ewallet-id').value = '';
        document.getElementById('ewallet-nama').value = '';
        document.getElementById('ewallet-kode').value = '';
        document.getElementById('ewallet-rekening').value = '';
        document.getElementById('ewallet-pemilik').value = 'RALIVA Fashion';
        document.getElementById('ewallet-deskripsi').value = '';
        document.getElementById('ewallet-status').value = 'aktif';
        document.getElementById('modal-ewallet').classList.remove('hidden');
        document.getElementById('modal-ewallet').classList.add('flex');
    }

    function closeEwalletForm() {
        document.getElementById('modal-ewallet').classList.add('hidden');
        document.getElementById('modal-ewallet').classList.remove('flex');
    }

    function editEwallet(id) {
        fetch(`/superadmin/data-bank/account/${id}/edit`)
            .then(r => r.json())
            .then(d => {
                document.getElementById('ewallet-modal-title').textContent = 'Edit E-Wallet';
                document.getElementById('form-ewallet').action = '{{ route('superadmin.data-bank.account.update', ['account' => ':ID']) }}'.replace(':ID', id);
                document.getElementById('ewallet-id').value = id;
                document.getElementById('ewallet-nama').value = d.nama;
                document.getElementById('ewallet-kode').value = d.kode;
                document.getElementById('ewallet-rekening').value = d.nomor_rekening || '';
                document.getElementById('ewallet-pemilik').value = d.nama_pemilik || 'RALIVA Fashion';
                document.getElementById('ewallet-deskripsi').value = d.deskripsi || '';
                document.getElementById('ewallet-status').value = d.status;
                document.getElementById('modal-ewallet').classList.remove('hidden');
                document.getElementById('modal-ewallet').classList.add('flex');
            });
    }

    // QRIS modal
    function openQrisForm() {
        document.getElementById('qris-modal-title').textContent = 'Tambah QRIS';
        document.getElementById('form-qris').action = '{{ route('superadmin.data-bank.account.store') }}';
        document.getElementById('qris-id').value = '';
        document.getElementById('modal-qris').classList.remove('hidden');
        document.getElementById('modal-qris').classList.add('flex');
    }

    function closeQrisForm() {
        document.getElementById('modal-qris').classList.add('hidden');
        document.getElementById('modal-qris').classList.remove('flex');
    }

    function editQris(id) {
        fetch(`/superadmin/data-bank/account/${id}/edit`)
            .then(r => r.json())
            .then(d => {
                document.getElementById('qris-modal-title').textContent = 'Edit QRIS';
                document.getElementById('form-qris').action = '{{ route('superadmin.data-bank.account.update', ['account' => ':ID']) }}'.replace(':ID', id);
                document.getElementById('qris-id').value = id;
                document.getElementById('qris-nama').value = d.nama;
                document.getElementById('qris-kode').value = d.kode;
                document.getElementById('qris-pemilik').value = d.nama_pemilik || 'RALIVA Fashion';
                document.getElementById('qris-deskripsi').value = d.deskripsi || '';
                document.getElementById('qris-status').value = d.status;
                document.getElementById('modal-qris').classList.remove('hidden');
                document.getElementById('modal-qris').classList.add('flex');
            });
    }
</script>
@endpush
@endsection
