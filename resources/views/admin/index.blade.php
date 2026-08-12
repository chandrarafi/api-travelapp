@extends('layouts.admin')

@section('title', 'Overview Dashboard')
@section('page_title', 'Dashboard Overview')
@section('page_subtitle', 'Ringkasan performa bisnis, pemesanan tiket, dan statistik armada')

@section('content')

    <!-- Top KPI Cards Grid (LIGHT THEME) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Pendapatan -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 relative overflow-hidden shadow-sm group hover:border-indigo-300 transition duration-300">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pendapatan</p>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <div class="flex items-center gap-1.5 mt-3 text-xs text-emerald-600 font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                <span>Pendapatan Lunas</span>
            </div>
        </div>

        <!-- Total Pemesanan -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 relative overflow-hidden shadow-sm group hover:border-indigo-300 transition duration-300">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pemesanan</p>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-200 flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($totalBookings) }} Tiket</h3>
            <div class="flex items-center gap-2 mt-3 text-xs text-slate-500">
                <span class="text-emerald-700 font-bold">{{ $lunasBookings }} Lunas</span>
                <span>•</span>
                <span class="text-amber-700 font-bold">{{ $pendingBookings }} Pending</span>
            </div>
        </div>

        <!-- Armada Aktif -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 relative overflow-hidden shadow-sm group hover:border-indigo-300 transition duration-300">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Armada / Mobil</p>
                <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4zM3 9h18M5 17h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mt-2">{{ $totalFleet }} Mobil</h3>
            <p class="text-xs text-slate-500 mt-3">Terhubung ke {{ $totalRoutes }} Rute Travel</p>
        </div>

        <!-- Total Pengguna -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 relative overflow-hidden shadow-sm group hover:border-indigo-300 transition duration-300">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengguna Terdaftar</p>
                <div class="w-10 h-10 rounded-xl bg-sky-50 border border-sky-200 flex items-center justify-center text-sky-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($totalUsers) }} Akun</h3>
            <p class="text-xs text-slate-500 mt-3">Pelanggan & Staff Admin</p>
        </div>

    </div>

    <!-- Pending Payments Notification Widget (If Any) -->
    @if($pendingPayments->count() > 0)
        <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-xs">
                        {{ $pendingPayments->count() }}
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-amber-900">Pembayaran Menunggu Konfirmasi</h4>
                        <p class="text-xs text-amber-800/80">Terdapat bukti transfer baru dari pelanggan yang perlu Anda konfirmasi.</p>
                    </div>
                </div>
                <a href="{{ route('admin.pembayaran.index') }}" 
                   class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs rounded-xl transition shadow-xs">
                    Lihat Semua
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($pendingPayments as $bayar)
                    <div class="bg-white border border-amber-200/80 rounded-xl p-3.5 flex items-center justify-between gap-3 shadow-xs">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center text-amber-800 font-mono text-xs font-bold shrink-0">
                                {{ strtoupper(substr($bayar->metode_pembayaran ?? 'TF', 0, 3)) }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $bayar->pemesanan->user->nama_lengkap ?? 'Pelanggan' }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">#{{ $bayar->pemesanan->kode_pemesanan ?? '' }} • Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <form action="{{ route('admin.pembayaran.konfirmasi', $bayar->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition shadow-xs">
                                Konfirmasi
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Middle Section: Sales Trend Chart & Top Routes -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Sales & Bookings Analytics Visualizer -->
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Tren Pemesanan & Pendapatan</h3>
                    <p class="text-xs text-slate-500">Statistik aktivitas transaksi bulanan</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">Tahun {{ date('Y') }}</span>
            </div>

            <!-- SVG Visualizer Chart (LIGHT THEME) -->
            <div class="h-56 w-full flex items-end justify-between gap-2 pt-8 pb-2 px-2 relative border-b border-slate-200">
                <!-- Grid Lines -->
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-40">
                    <div class="border-b border-slate-200 w-full"></div>
                    <div class="border-b border-slate-200 w-full"></div>
                    <div class="border-b border-slate-200 w-full"></div>
                </div>

                @php
                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                    $heights = [35, 45, 60, 50, 75, 90, 80, 95, 70, 85, 90, 100];
                @endphp

                @foreach($months as $idx => $m)
                    <div class="flex-1 flex flex-col items-center gap-2 group relative z-10">
                        <!-- Tooltip -->
                        <div class="opacity-0 group-hover:opacity-100 transition absolute -top-8 bg-slate-900 text-white text-[10px] py-1 px-2 rounded font-mono shadow-md pointer-events-none whitespace-nowrap">
                            {{ $m }}: Rp {{ number_format(($heights[$idx] * 120000), 0, ',', '.') }}
                        </div>
                        <div class="w-full bg-indigo-50 group-hover:bg-indigo-100 rounded-t-lg transition-all duration-300 relative overflow-hidden" 
                             style="height: {{ $heights[$idx] }}%;">
                            <div class="absolute inset-x-0 top-0 h-1.5 bg-indigo-600 group-hover:h-full transition-all duration-300 opacity-90"></div>
                        </div>
                        <span class="text-[10px] text-slate-500 font-mono font-semibold group-hover:text-indigo-600 transition">{{ $m }}</span>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500 mt-4">
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1.5 font-medium">
                        <span class="w-3 h-3 rounded-sm bg-indigo-600"></span> Total Tiket Terjual
                    </span>
                    <span class="flex items-center gap-1.5 font-medium">
                        <span class="w-3 h-3 rounded-sm bg-indigo-100 border border-indigo-200"></span> Target Kuota
                    </span>
                </div>
                <span class="text-emerald-700 font-bold">+14.2% dibanding bulan lalu</span>
            </div>
        </div>

        <!-- Top Routes -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900">Rute Terpopuler</h3>
                    <a href="{{ route('admin.rute.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Kelola Rute &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($topRoutes as $rute)
                        <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl flex items-center justify-between hover:border-slate-300 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800">{{ $rute->kota_asal }} &rarr; {{ $rute->kota_tujuan }}</h4>
                                    <p class="text-[11px] text-slate-500">{{ $rute->jarak_km ? $rute->jarak_km . ' km' : 'Jarak normal' }} • {{ $rute->mobil_count }} Mobil</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-mono font-bold">
                                {{ $rute->booking_count }} Pemesanan
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-4 text-center">Belum ada rute terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Action Shortcut -->
            <div class="mt-6 pt-4 border-t border-slate-200 flex items-center gap-2">
                <a href="{{ route('admin.mobil.index') }}" 
                   class="flex-1 py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl text-center transition shadow-sm">
                    + Tambah Armada
                </a>
                <a href="{{ route('admin.rute.index') }}" 
                   class="flex-1 py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs rounded-xl text-center transition border border-slate-200">
                    + Tambah Rute
                </a>
            </div>
        </div>

    </div>

    <!-- Recent Bookings Table (LIGHT THEME) -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Pemesanan Tiket Terbaru</h3>
                <p class="text-xs text-slate-500">Daftar transaksi keberangkatan yang baru saja dipesan pelanggan</p>
            </div>
            <a href="{{ route('admin.pemesanan.index') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-indigo-700 text-xs font-bold rounded-xl border border-slate-200 transition">
                Lihat Semua Pemesanan &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Kode Booking</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Armada & Rute</th>
                        <th class="px-4 py-3">Tgl Keberangkatan</th>
                        <th class="px-4 py-3">Jumlah Kursi</th>
                        <th class="px-4 py-3">Total Bayar</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80">
                    @forelse($recentBookings as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-4 py-3.5 font-mono font-bold text-indigo-700">
                                #{{ $b->kode_pemesanan }}
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="font-bold text-slate-900">{{ $b->user->nama_lengkap ?? 'Pelanggan' }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $b->user->nomor_telepon ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="font-semibold text-slate-800">{{ $b->mobil->nama_mobil ?? '-' }}</p>
                                <p class="text-[11px] text-slate-500">{{ $b->mobil->rute->kota_asal ?? '' }} &rarr; {{ $b->mobil->rute->kota_tujuan ?? '' }}</p>
                            </td>
                            <td class="px-4 py-3.5 font-mono text-slate-700">
                                {{ \Carbon\Carbon::parse($b->tanggal_keberangkatan)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3.5 font-bold text-slate-800">
                                {{ $b->jumlah_kursi }} Kursi
                            </td>
                            <td class="px-4 py-3.5 font-mono font-bold text-slate-900">
                                Rp {{ number_format($b->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3.5">
                                @if($b->status_pembayaran === 'lunas')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lunas
                                    </span>
                                @elseif($b->status_pembayaran === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Batal
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">Belum ada pemesanan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
