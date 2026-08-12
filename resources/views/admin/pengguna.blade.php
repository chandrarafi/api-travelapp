@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('page_title', 'Manajemen Pengguna & Staff Admin')
@section('page_subtitle', 'Kelola hak akses pengguna, tambah akun admin baru, dan edit profil pengguna')

@section('content')

    <div x-data="{ addModalOpen: false, editModalOpen: false, editUser: {} }">
        
        <!-- Header & Controls -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            
            <div class="flex items-center gap-1.5 p-1 bg-white border border-slate-200 rounded-xl shadow-xs">
                <a href="{{ route('admin.pengguna.index') }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ !request('peran') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Semua ({{ \App\Models\User::count() }})
                </a>
                <a href="{{ route('admin.pengguna.index', ['peran' => 'admin']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('peran') === 'admin' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Admin ({{ \App\Models\User::where('peran', 'admin')->count() }})
                </a>
                <a href="{{ route('admin.pengguna.index', ['peran' => 'pelanggan']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('peran') === 'pelanggan' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Pelanggan ({{ \App\Models\User::where('peran', 'pelanggan')->count() }})
                </a>
            </div>

            <div class="flex items-center gap-3">
                <form action="{{ route('admin.pengguna.index') }}" method="GET" class="flex items-center gap-2">
                    @if(request('peran'))
                        <input type="hidden" name="peran" value="{{ request('peran') }}">
                    @endif
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama / Email..." 
                               class="w-64 pl-9 pr-4 py-1.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-600 shadow-xs">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold border border-slate-200">Cari</button>
                </form>

                <button @click="addModalOpen = true" 
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-indigo-600/20 flex items-center gap-2 transition shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pengguna
                </button>
            </div>
        </div>

        <!-- Table View (LIGHT THEME) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">Nama & Email</th>
                            <th class="px-5 py-3.5">Nomor Telepon</th>
                            <th class="px-5 py-3.5">Peran (Role)</th>
                            <th class="px-5 py-3.5">Total Pemesanan</th>
                            <th class="px-5 py-3.5">Tanggal Terdaftar</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80">
                        @forelse($penggunas as $u)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-indigo-100 border border-indigo-200 text-indigo-700 font-bold flex items-center justify-center text-sm shrink-0">
                                            {{ strtoupper(substr($u->nama_lengkap, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $u->nama_lengkap }}</p>
                                            <p class="text-[11px] text-slate-500 font-mono">{{ $u->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-mono text-slate-700">
                                    {{ $u->nomor_telepon ?? '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    @if($u->peran === 'admin')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span> Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Pelanggan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-mono font-bold text-slate-800">
                                    {{ $u->pemesanan_count }} Pesanan
                                </td>
                                <td class="px-5 py-4 font-mono text-slate-500">
                                    {{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="editUser = { id: {{ $u->id }}, nama_lengkap: '{{ addslashes($u->nama_lengkap) }}', email: '{{ addslashes($u->email) }}', nomor_telepon: '{{ addslashes($u->nomor_telepon) }}', peran: '{{ $u->peran }}' }; editModalOpen = true" 
                                                class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-indigo-700 rounded-lg font-semibold transition text-xs flex items-center gap-1 border border-slate-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>

                                        @if($u->id !== Auth::id())
                                            <form action="{{ route('admin.pengguna.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg font-semibold transition text-xs border border-rose-200">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-400">Tidak ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200">
                {{ $penggunas->links() }}
            </div>
        </div>

        <!-- Add User Modal (LIGHT THEME) -->
        <div x-cloak x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="addModalOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs"></div>
            
            <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md p-6 relative z-10 shadow-2xl">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">Tambah Akun Pengguna / Admin</h3>
                    <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form action="{{ route('admin.pengguna.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" required placeholder="Ahmad Rizky" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" required placeholder="user@example.com" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi (Minimal 6 karakter)</label>
                        <input type="password" name="kata_sandi" required placeholder="••••••••" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" placeholder="08123456789" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Peran (Role)</label>
                        <select name="peran" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                            <option value="pelanggan">Pelanggan</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md">Simpan Akun</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit User Modal (LIGHT THEME) -->
        <div x-cloak x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="editModalOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs"></div>
            
            <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md p-6 relative z-10 shadow-2xl">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">Edit Pengguna</h3>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form :action="'/admin/pengguna/' + editUser.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" x-model="editUser.nama_lengkap" required 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="editUser.email" required 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Baru (Opsional)</label>
                        <input type="password" name="kata_sandi" placeholder="Isi hanya jika ingin mengubah kata sandi" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" x-model="editUser.nomor_telepon" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Peran (Role)</label>
                        <select name="peran" x-model="editUser.peran" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                            <option value="pelanggan">Pelanggan</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md">Update Pengguna</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
