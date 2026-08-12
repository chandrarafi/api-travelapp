@extends('layouts.admin')

@section('title', 'Data Pemesanan')
@section('page_title', 'Manajemen Pemesanan Tiket')
@section('page_subtitle', 'Pantau seluruh pesanan tiket travel pelanggan dan kelola status reservasi')

@section('content')

    <div x-data="{ statusModalOpen: false, selectedBooking: {} }">
        
        <!-- Header & Filters -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            
            <!-- Status Filter Tabs (LIGHT THEME) -->
            <div class="flex items-center gap-1.5 p-1 bg-white border border-slate-200 rounded-xl shadow-xs">
                <a href="{{ route('admin.pemesanan.index') }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ !request('status') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Semua ({{ \App\Models\Pemesanan::count() }})
                </a>
                <a href="{{ route('admin.pemesanan.index', ['status' => 'pending']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Pending ({{ \App\Models\Pemesanan::where('status_pembayaran', 'pending')->count() }})
                </a>
                <a href="{{ route('admin.pemesanan.index', ['status' => 'lunas']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'lunas' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Lunas ({{ \App\Models\Pemesanan::where('status_pembayaran', 'lunas')->count() }})
                </a>
                <a href="{{ route('admin.pemesanan.index', ['status' => 'batal']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'batal' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Batal ({{ \App\Models\Pemesanan::where('status_pembayaran', 'batal')->count() }})
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.pemesanan.index') }}" method="GET" class="flex items-center gap-2">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Kode / Nama..." 
                           class="w-64 pl-9 pr-4 py-1.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-600 shadow-xs">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <button type="submit" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-xs">Cari</button>
            </form>
        </div>

        <!-- Bookings Table (LIGHT THEME) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">Kode Pemesanan</th>
                            <th class="px-5 py-3.5">Pelanggan</th>
                            <th class="px-5 py-3.5">Armada & Rute</th>
                            <th class="px-5 py-3.5">Tgl Keberangkatan</th>
                            <th class="px-5 py-3.5">Kursi</th>
                            <th class="px-5 py-3.5">Total Bayar</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80">
                        @forelse($pemesanans as $p)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-4 font-mono font-bold text-indigo-700">
                                    #{{ $p->kode_pemesanan }}
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-900">{{ $p->user->nama_lengkap ?? 'Pelanggan' }}</p>
                                    <p class="text-[11px] text-slate-500 font-mono">{{ $p->user->nomor_telepon ?? $p->user->email ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-800">{{ $p->mobil->nama_mobil ?? '-' }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $p->mobil->rute->kota_asal ?? '' }} &rarr; {{ $p->mobil->rute->kota_tujuan ?? '' }}</p>
                                </td>
                                <td class="px-5 py-4 font-mono text-slate-700">
                                    {{ \Carbon\Carbon::parse($p->tanggal_keberangkatan)->format('d M Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 font-mono font-bold text-[11px] border border-slate-200">
                                        {{ $p->jumlah_kursi }} Kursi
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-mono font-bold text-slate-900">
                                    Rp {{ number_format($p->total_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4">
                                    @if($p->status_pembayaran === 'lunas')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lunas
                                        </span>
                                    @elseif($p->status_pembayaran === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Batal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="selectedBooking = {{ json_encode($p) }}; statusModalOpen = true" 
                                                class="px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg font-semibold transition text-xs border border-indigo-200 flex items-center gap-1">
                                            Status
                                        </button>

                                        <form action="{{ route('admin.pemesanan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data pemesanan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg font-semibold transition text-xs border border-rose-200">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-slate-400">Tidak ada pemesanan yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200">
                {{ $pemesanans->links() }}
            </div>
        </div>

        <!-- Edit Status Modal (LIGHT THEME) -->
        <div x-cloak x-show="statusModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="statusModalOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs"></div>
            
            <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md p-6 relative z-10 shadow-2xl">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">Ubah Status Pemesanan</h3>
                    <button @click="statusModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form :action="'/admin/pemesanan/' + selectedBooking.id + '/status'" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs space-y-1">
                        <p class="text-slate-600">Kode Pemesanan: <span class="font-mono font-bold text-indigo-700" x-text="'#' + selectedBooking.kode_pemesanan"></span></p>
                        <p class="text-slate-600">Pelanggan: <span class="text-slate-900 font-bold" x-text="selectedBooking.user?.nama_lengkap"></span></p>
                        <p class="text-slate-600">Total: <span class="text-emerald-700 font-mono font-bold" x-text="'Rp ' + Number(selectedBooking.total_bayar).toLocaleString('id-ID')"></span></p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status Pemesanan Baru</label>
                        <select name="status_pembayaran" x-model="selectedBooking.status_pembayaran" required 
                                class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                            <option value="pending">PENDING (Menunggu Pembayaran / Verifikasi)</option>
                            <option value="lunas">LUNAS (Pembayaran Diterima & Tiket Aktif)</option>
                            <option value="batal">BATAL (Pemesanan Dibatalkan)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="statusModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md">Simpan Status</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
