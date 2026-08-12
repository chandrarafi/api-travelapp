@extends('layouts.admin')

@section('title', 'Manajemen Armada')
@section('page_title', 'Manajemen Armada Mobil Travel')
@section('page_subtitle', 'Kelola kendaraan, jadwal keberangkatan, harga tiket, dan kapasitas kursi')

@section('content')

    <div x-data="{ addModalOpen: false, editModalOpen: false, editMobil: {} }">
        
        <!-- Action Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Daftar Armada Travel</h2>
                <p class="text-xs text-slate-500">Total {{ $mobils->total() }} Kendaraan Aktif Beroperasi</p>
            </div>
            
            <button @click="addModalOpen = true" 
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-indigo-600/20 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Mobil Travel Baru
            </button>
        </div>

        <!-- Table View (LIGHT THEME) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">Mobil & Foto</th>
                            <th class="px-5 py-3.5">Nomor Plat</th>
                            <th class="px-5 py-3.5">Rute Perjalanan</th>
                            <th class="px-5 py-3.5">Jam Keberangkatan</th>
                            <th class="px-5 py-3.5">Harga Tiket</th>
                            <th class="px-5 py-3.5">Kapasitas Kursi</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80">
                        @forelse($mobils as $m)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $m->foto ?? 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=600' }}" 
                                             alt="{{ $m->nama_mobil }}" 
                                             class="w-12 h-12 rounded-xl object-cover border border-slate-200 shrink-0 shadow-xs">
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $m->nama_mobil }}</p>
                                            <p class="text-[11px] text-slate-400">ID: #{{ $m->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-mono text-indigo-700 border border-slate-200 font-bold text-xs">
                                        {{ $m->nomor_plat }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-semibold text-slate-800">
                                    {{ $m->rute->kota_asal ?? 'Padang' }} &rarr; {{ $m->rute->kota_tujuan ?? 'Bukittinggi' }}
                                </td>
                                <td class="px-5 py-4 font-mono text-emerald-700 font-bold">
                                    {{ $m->jam_keberangkatan }}
                                </td>
                                <td class="px-5 py-4 font-mono font-bold text-slate-900">
                                    Rp {{ number_format($m->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 font-bold text-[11px]">
                                        {{ $m->total_kursi }} Kursi
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="editMobil = { id: {{ $m->id }}, rute_id: '{{ $m->rute_id }}', nama_mobil: '{{ addslashes($m->nama_mobil) }}', nomor_plat: '{{ addslashes($m->nomor_plat) }}', jam_keberangkatan: '{{ addslashes($m->jam_keberangkatan) }}', harga: '{{ $m->harga }}', foto: '{{ addslashes($m->foto) }}' }; editModalOpen = true" 
                                                class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-indigo-700 rounded-lg font-semibold transition text-xs flex items-center gap-1 border border-slate-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.mobil.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus armada ini?')">
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
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400">Belum ada data armada mobil.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200">
                {{ $mobils->links() }}
            </div>
        </div>

        <!-- Add Modal (LIGHT THEME) -->
        <div x-cloak x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="addModalOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs"></div>
            
            <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-lg p-6 relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">Tambah Armada Mobil Baru</h3>
                    <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form action="{{ route('admin.mobil.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Rute Perjalanan</label>
                        <select name="rute_id" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                            <option value="">-- Pilih Rute --</option>
                            @foreach($rutes as $r)
                                <option value="{{ $r->id }}">{{ $r->kota_asal }} &rarr; {{ $r->kota_tujuan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Mobil / Travel</label>
                            <input type="text" name="nama_mobil" required placeholder="Toyota HiAce Premio" 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Plat</label>
                            <input type="text" name="nomor_plat" required placeholder="BA 1234 BKT" 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Keberangkatan</label>
                            <input type="text" name="jam_keberangkatan" required placeholder="08:00 WIB" 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Tiket (Rp)</label>
                            <input type="number" name="harga" required placeholder="65000" 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kapasitas Jumlah Kursi</label>
                        <input type="number" name="total_kursi" value="10" min="1" max="60" required 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                        <p class="text-[11px] text-slate-400 mt-1">*Nomor kursi akan otomatis digenerate (Contoh: 1A, 1B, 2A, dst)</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">URL Foto Mobil</label>
                        <input type="url" name="foto" placeholder="https://images.unsplash.com/..." 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md">Simpan Mobil</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal (LIGHT THEME) -->
        <div x-cloak x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="editModalOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs"></div>
            
            <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-lg p-6 relative z-10 shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">Edit Informasi Armada</h3>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form :action="'/admin/mobil/' + editMobil.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Rute Perjalanan</label>
                        <select name="rute_id" x-model="editMobil.rute_id" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                            @foreach($rutes as $r)
                                <option value="{{ $r->id }}">{{ $r->kota_asal }} &rarr; {{ $r->kota_tujuan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Mobil / Travel</label>
                            <input type="text" name="nama_mobil" x-model="editMobil.nama_mobil" required 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Plat</label>
                            <input type="text" name="nomor_plat" x-model="editMobil.nomor_plat" required 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Keberangkatan</label>
                            <input type="text" name="jam_keberangkatan" x-model="editMobil.jam_keberangkatan" required 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Tiket (Rp)</label>
                            <input type="number" name="harga" x-model="editMobil.harga" required 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">URL Foto Mobil</label>
                        <input type="url" name="foto" x-model="editMobil.foto" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md">Update Mobil</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
