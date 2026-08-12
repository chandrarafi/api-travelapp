@extends('layouts.admin')

@section('title', 'Konfirmasi Pembayaran')
@section('page_title', 'Verifikasi & Konfirmasi Pembayaran')
@section('page_subtitle', 'Periksa bukti transfer pelanggan dan verifikasi transaksi pembayaran tiket')

@section('content')

    <div x-data="{ imgModalOpen: false, currentImg: '', currentTitle: '' }">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Daftar Transaksi Pembayaran</h2>
                <p class="text-xs text-slate-500">Total {{ $pembayarans->total() }} Transaksi Pembayaran Terdaftar</p>
            </div>
        </div>

        <!-- Table View (LIGHT THEME) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">ID & Tanggal</th>
                            <th class="px-5 py-3.5">Kode Booking</th>
                            <th class="px-5 py-3.5">Pelanggan</th>
                            <th class="px-5 py-3.5">Metode Bayar</th>
                            <th class="px-5 py-3.5">Jumlah Bayar</th>
                            <th class="px-5 py-3.5">Bukti Transfer</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80">
                        @forelse($pembayarans as $p)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-4">
                                    <p class="font-mono text-slate-500 font-semibold">#BAYAR-{{ $p->id }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('d/m/Y H:i') }}</p>
                                </td>
                                <td class="px-5 py-4 font-mono font-bold text-indigo-700">
                                    #{{ $p->pemesanan->kode_pemesanan ?? '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-900">{{ $p->pemesanan->user->nama_lengkap ?? 'Pelanggan' }}</p>
                                    <p class="text-[11px] text-slate-500 font-mono">{{ $p->pemesanan->user->email ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4 font-semibold text-slate-800">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 font-mono text-[11px] border border-slate-200 font-bold">
                                        {{ strtoupper($p->metode_pembayaran) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-mono font-bold text-emerald-700">
                                    Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4">
                                    @if($p->bukti_pembayaran)
                                        <button @click="currentImg = '{{ $p->bukti_pembayaran }}'; currentTitle = 'Bukti Transfer #{{ $p->pemesanan->kode_pemesanan ?? '' }}'; imgModalOpen = true" 
                                                class="flex items-center gap-2 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-indigo-700 rounded-lg text-[11px] font-bold border border-slate-200 transition">
                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Lihat Bukti
                                        </button>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Belum diunggah</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if($p->status === 'berhasil')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Berhasil (Lunas)
                                        </span>
                                    @elseif($p->status === 'menunggu')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu Verifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Gagal / Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if($p->status === 'menunggu')
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route('admin.pembayaran.konfirmasi', $p->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-xs transition">
                                                    Konfirmasi
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.pembayaran.tolak', $p->id) }}" method="POST" onsubmit="return confirm('Tolak pembayaran ini?')">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold border border-rose-200 transition">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-400 font-medium">Telah Diverifikasi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-slate-400">Belum ada transaksi pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200">
                {{ $pembayarans->links() }}
            </div>
        </div>

        <!-- Lightbox Modal for Bukti Pembayaran (LIGHT THEME) -->
        <div x-cloak x-show="imgModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="imgModalOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs"></div>
            
            <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-xl p-6 relative z-10 shadow-2xl">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900" x-text="currentTitle"></h3>
                    <button @click="imgModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <div class="flex items-center justify-center p-2 bg-slate-50 rounded-xl border border-slate-200 overflow-hidden min-h-[250px]">
                    <img :src="currentImg" alt="Bukti Transfer" class="max-h-[60vh] object-contain rounded-lg shadow-sm">
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <a :href="currentImg" target="_blank" class="text-xs font-semibold text-indigo-600 hover:underline">Buka foto di tab baru &rarr;</a>
                    <button type="button" @click="imgModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Tutup</button>
                </div>
            </div>
        </div>

    </div>

@endsection
