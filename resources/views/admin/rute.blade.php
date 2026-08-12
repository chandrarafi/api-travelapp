@extends('layouts.admin')

@section('title', 'Manajemen Rute')
@section('page_title', 'Manajemen Rute Perjalanan')
@section('page_subtitle', 'Kelola destinasi kota asal, kota tujuan, dan jarak antar wilayah')

@section('content')

    <div x-data="{ addModalOpen: false, editModalOpen: false, editRute: {} }">
        
        <!-- Action Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Daftar Rute Travel</h2>
                <p class="text-xs text-slate-500">Total {{ $rutes->total() }} Rute Terdaftar dalam sistem</p>
            </div>
            
            <button @click="addModalOpen = true" 
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-indigo-600/20 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Rute Baru
            </button>
        </div>

        <!-- Table View (LIGHT THEME) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">ID</th>
                            <th class="px-5 py-3.5">Kota Asal</th>
                            <th class="px-5 py-3.5">Kota Tujuan</th>
                            <th class="px-5 py-3.5">Jarak (KM)</th>
                            <th class="px-5 py-3.5">Jumlah Mobil</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80">
                        @forelse($rutes as $rute)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-4 font-mono text-slate-400">#{{ $rute->id }}</td>
                                <td class="px-5 py-4 font-bold text-slate-900 flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($rute->kota_asal, 0, 1)) }}
                                    </div>
                                    <span>{{ $rute->kota_asal }}</span>
                                </td>
                                <td class="px-5 py-4 font-bold text-indigo-700">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        <span>{{ $rute->kota_tujuan }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-mono text-slate-700 font-semibold">
                                    {{ $rute->jarak_km ? $rute->jarak_km . ' KM' : '-' }}
                                </td>
                                <td class="px-5 py-4 font-semibold text-slate-800">
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 border border-slate-200 text-[11px]">
                                        {{ $rute->mobil_count }} Mobil
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="editRute = { id: {{ $rute->id }}, kota_asal: '{{ addslashes($rute->kota_asal) }}', kota_tujuan: '{{ addslashes($rute->kota_tujuan) }}', jarak_km: '{{ $rute->jarak_km }}' }; editModalOpen = true" 
                                                class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-indigo-700 rounded-lg font-semibold transition text-xs flex items-center gap-1 border border-slate-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.rute.destroy', $rute->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rute ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg font-semibold transition text-xs border border-rose-200 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada data rute.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200">
                {{ $rutes->links() }}
            </div>
        </div>

        <!-- Add Modal (LIGHT THEME) -->
        <div x-cloak x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="addModalOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs"></div>
            
            <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md p-6 relative z-10 shadow-2xl">
                <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">Tambah Rute Baru</h3>
                    <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form action="{{ route('admin.rute.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kota Asal</label>
                        <input type="text" name="kota_asal" required placeholder="Contoh: Padang" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kota Tujuan</label>
                        <input type="text" name="kota_tujuan" required placeholder="Contoh: Bukittinggi" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jarak Perjalanan (KM)</label>
                        <input type="number" name="jarak_km" placeholder="Contoh: 90" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md">Simpan Rute</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal (LIGHT THEME) -->
        <div x-cloak x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="editModalOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs"></div>
            
            <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md p-6 relative z-10 shadow-2xl">
                <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">Edit Rute Perjalanan</h3>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form :action="'/admin/rute/' + editRute.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kota Asal</label>
                        <input type="text" name="kota_asal" x-model="editRute.kota_asal" required 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kota Tujuan</label>
                        <input type="text" name="kota_tujuan" x-model="editRute.kota_tujuan" required 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jarak Perjalanan (KM)</label>
                        <input type="number" name="jarak_km" x-model="editRute.jarak_km" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md">Update Rute</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
