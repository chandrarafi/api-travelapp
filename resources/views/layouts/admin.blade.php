<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 text-slate-800">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Travel</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full font-sans antialiased bg-slate-100/70 text-slate-800 selection:bg-indigo-600 selection:text-white" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Backdrop for mobile -->
        <div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40 md:hidden"></div>

        <!-- Sidebar Navigation (LIGHT THEME) -->
        <aside class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 shadow-sm flex flex-col justify-between transform transition-transform duration-300 ease-in-out md:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <div>
                <!-- Brand Header -->
                <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 p-0.5 shadow-md shadow-indigo-600/20">
                            <div class="w-full h-full bg-indigo-600 rounded-[10px] flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <span class="font-bold text-lg text-slate-900">Travel</span>
                            <span class="block text-[10px] font-bold tracking-wider text-indigo-600 uppercase">Admin Portal</span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1">
                    <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Utama</p>

                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/80 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Overview Dashboard
                    </a>

                    <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-6 mb-2">Manajemen</p>

                    <a href="{{ route('admin.rute.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.rute.*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/80 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.rute.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Rute Perjalanan
                    </a>

                    <a href="{{ route('admin.mobil.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.mobil.*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/80 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.mobil.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 17a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4zM3 9h18M5 17h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                        Armada / Mobil
                    </a>

                    <a href="{{ route('admin.pemesanan.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.pemesanan.*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/80 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.pemesanan.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        Data Pemesanan
                    </a>

                    <a href="{{ route('admin.pembayaran.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.pembayaran.*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/80 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.pembayaran.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Konfirmasi Pembayaran
                    </a>

                    <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-6 mb-2">Pengaturan</p>

                    <a href="{{ route('admin.pengguna.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.pengguna.*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/80 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.pengguna.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Kelola Pengguna
                    </a>
                </nav>
            </div>

            <!-- Admin Profile & Logout -->
            <div class="p-4 border-t border-slate-200 bg-slate-50/70">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-sm shadow-indigo-600/30">
                            {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'A', 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->nama_lengkap ?? 'Admin' }}</p>
                            <p class="text-xs text-indigo-600 font-mono truncate">{{ Auth::user()->email ?? 'admin@travel.com' }}</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" title="Keluar"
                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Workspace (LIGHT THEME) -->
        <div class="flex-1 flex flex-col min-w-0 bg-slate-100/70">
            <!-- Top Navbar -->
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden text-slate-500 hover:text-slate-700 p-2 rounded-lg hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-base font-bold text-slate-900">@yield('page_title', 'Dashboard')</h1>
                        <p class="text-xs text-slate-500">@yield('page_subtitle', 'Selamat datang di Panel Kontrol Travel')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        System Online
                    </span>
                </div>
            </header>

            <!-- Alerts Container -->
            <div class="px-6 pt-4">
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition
                    class="flex items-center justify-between p-4 mb-2 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition
                    class="flex items-center justify-between p-4 mb-2 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-500 hover:text-rose-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                @endif

                @if($errors->any())
                <div x-data="{ show: true }" x-show="show" x-transition
                    class="p-4 mb-2 bg-amber-50 border border-amber-200 text-amber-900 rounded-xl text-sm shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold flex items-center gap-2 text-amber-800">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Mohon koreksi kesalahan berikut:
                        </span>
                        <button @click="show = false" class="text-amber-500 hover:text-amber-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-xs text-amber-800">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-6 space-y-6">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="p-6 border-t border-slate-200 bg-white/50 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Travel Admin Dashboard. Built with Laravel & Tailwind CSS.</p>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
