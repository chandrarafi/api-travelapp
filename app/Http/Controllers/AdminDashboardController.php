<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rute;
use App\Models\Mobil;
use App\Models\Kursi;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    // --- AUTHENTICATION ---
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->peran === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'kata_sandi' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'kata_sandi.required' => 'Kata sandi wajib diisi.',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->kata_sandi], $request->remember)) {
            $user = Auth::user();

            if ($user->peran !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Akses ditolak. Akun Anda bukan admin.'])->withInput();
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, ' . $user->nama_lengkap . '!');
        }

        return back()->withErrors(['email' => 'Email atau kata sandi tidak cocok.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar.');
    }

    // --- DASHBOARD OVERVIEW ---
    public function index()
    {
        $totalRevenue = Pembayaran::where('status', 'berhasil')->sum('jumlah_bayar');
        if ($totalRevenue == 0) {
            $totalRevenue = Pemesanan::where('status_pembayaran', 'lunas')->sum('total_bayar');
        }

        $totalBookings = Pemesanan::count();
        $pendingBookings = Pemesanan::where('status_pembayaran', 'pending')->count();
        $lunasBookings = Pemesanan::where('status_pembayaran', 'lunas')->count();
        $batalBookings = Pemesanan::where('status_pembayaran', 'batal')->count();
        $totalFleet = Mobil::count();
        $totalRoutes = Rute::count();
        $totalUsers = User::count();

        $recentBookings = Pemesanan::with(['user', 'mobil.rute'])
            ->latest()
            ->take(6)
            ->get();

        $pendingPayments = Pembayaran::with(['pemesanan.user', 'pemesanan.mobil'])
            ->where('status', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        $topRoutes = Rute::withCount('mobil')
            ->get()
            ->map(function ($rute) {
                $bookingCount = Pemesanan::whereHas('mobil', function ($q) use ($rute) {
                    $q->where('rute_id', $rute->id);
                })->count();
                $rute->booking_count = $bookingCount;
                return $rute;
            })
            ->sortByDesc('booking_count')
            ->take(5);

        return view('admin.index', compact(
            'totalRevenue',
            'totalBookings',
            'pendingBookings',
            'lunasBookings',
            'batalBookings',
            'totalFleet',
            'totalRoutes',
            'totalUsers',
            'recentBookings',
            'pendingPayments',
            'topRoutes'
        ));
    }

    // --- RUTE MANAGEMENT ---
    public function ruteIndex()
    {
        $rutes = Rute::withCount('mobil')->latest()->paginate(10);
        return view('admin.rute', compact('rutes'));
    }

    public function ruteStore(Request $request)
    {
        $request->validate([
            'kota_asal' => 'required|string|max:255',
            'kota_tujuan' => 'required|string|max:255',
            'jarak_km' => 'nullable|numeric|min:1',
        ], [
            'kota_asal.required' => 'Kota asal wajib diisi.',
            'kota_tujuan.required' => 'Kota tujuan wajib diisi.',
        ]);

        Rute::create([
            'kota_asal' => $request->kota_asal,
            'kota_tujuan' => $request->kota_tujuan,
            'jarak_km' => $request->jarak_km,
        ]);

        return redirect()->route('admin.rute.index')->with('success', 'Rute perjalanan berhasil ditambahkan.');
    }

    public function ruteUpdate(Request $request, $id)
    {
        $rute = Rute::findOrFail($id);
        $request->validate([
            'kota_asal' => 'required|string|max:255',
            'kota_tujuan' => 'required|string|max:255',
            'jarak_km' => 'nullable|numeric|min:1',
        ]);

        $rute->update([
            'kota_asal' => $request->kota_asal,
            'kota_tujuan' => $request->kota_tujuan,
            'jarak_km' => $request->jarak_km,
        ]);

        return redirect()->route('admin.rute.index')->with('success', 'Data rute berhasil diperbarui.');
    }

    public function ruteDestroy($id)
    {
        $rute = Rute::findOrFail($id);
        $rute->delete();
        return redirect()->route('admin.rute.index')->with('success', 'Rute berhasil dihapus.');
    }

    // --- MOBIL / FLEET MANAGEMENT ---
    public function mobilIndex()
    {
        $mobils = Mobil::with('rute')->withCount('kursi')->latest()->paginate(10);
        $rutes = Rute::all();
        return view('admin.mobil', compact('mobils', 'rutes'));
    }

    public function mobilStore(Request $request)
    {
        $request->validate([
            'rute_id' => 'required|exists:rute,id',
            'nama_mobil' => 'required|string|max:255',
            'nomor_plat' => 'required|string|max:50',
            'jam_keberangkatan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'total_kursi' => 'required|integer|min:1|max:60',
            'foto' => 'nullable|string|max:500',
        ], [
            'rute_id.required' => 'Pilih rute perjalanan.',
            'nama_mobil.required' => 'Nama mobil/travel wajib diisi.',
            'nomor_plat.required' => 'Nomor plat mobil wajib diisi.',
            'jam_keberangkatan.required' => 'Jam keberangkatan wajib diisi.',
            'harga.required' => 'Harga tiket wajib diisi.',
        ]);

        $mobil = Mobil::create([
            'rute_id' => $request->rute_id,
            'nama_mobil' => $request->nama_mobil,
            'nomor_plat' => $request->nomor_plat,
            'jam_keberangkatan' => $request->jam_keberangkatan,
            'harga' => $request->harga,
            'total_kursi' => $request->total_kursi,
            'foto' => $request->foto ?? 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=600',
        ]);

        // Auto-generate kursi for this mobil
        $baris = ['A', 'B', 'C', 'D'];
        for ($i = 1; $i <= $request->total_kursi; $i++) {
            $nomorBaris = ceil($i / 2);
            $huruf = $baris[($i - 1) % 2];
            $nomorKursi = $nomorBaris . $huruf;

            Kursi::create([
                'mobil_id' => $mobil->id,
                'nomor_kursi' => $nomorKursi,
                'status' => 'tersedia',
            ]);
        }

        return redirect()->route('admin.mobil.index')->with('success', 'Mobil travel & kursi berhasil ditambahkan.');
    }

    public function mobilUpdate(Request $request, $id)
    {
        $mobil = Mobil::findOrFail($id);
        $request->validate([
            'rute_id' => 'required|exists:rute,id',
            'nama_mobil' => 'required|string|max:255',
            'nomor_plat' => 'required|string|max:50',
            'jam_keberangkatan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|string|max:500',
        ]);

        $mobil->update([
            'rute_id' => $request->rute_id,
            'nama_mobil' => $request->nama_mobil,
            'nomor_plat' => $request->nomor_plat,
            'jam_keberangkatan' => $request->jam_keberangkatan,
            'harga' => $request->harga,
            'foto' => $request->foto ?? $mobil->foto,
        ]);

        return redirect()->route('admin.mobil.index')->with('success', 'Informasi mobil berhasil diperbarui.');
    }

    public function mobilDestroy($id)
    {
        $mobil = Mobil::findOrFail($id);
        $mobil->delete();
        return redirect()->route('admin.mobil.index')->with('success', 'Mobil berhasil dihapus.');
    }

    // --- PEMESANAN / BOOKINGS MANAGEMENT ---
    public function pemesananIndex(Request $request)
    {
        $query = Pemesanan::with(['user', 'mobil.rute', 'pembayaran', 'detailPemesanan.kursi']);

        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('kode_pemesanan', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('nama_lengkap', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $pemesanans = $query->latest()->paginate(10);
        return view('admin.pemesanan', compact('pemesanans'));
    }

    public function pemesananShow($id)
    {
        $pemesanan = Pemesanan::with(['user', 'mobil.rute', 'pembayaran', 'detailPemesanan.kursi'])->findOrFail($id);
        return response()->json([
            'sukses' => true,
            'data' => $pemesanan
        ]);
    }

    public function pemesananUpdateStatus(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $request->validate([
            'status_pembayaran' => 'required|in:pending,lunas,batal',
        ]);

        $pemesanan->update([
            'status_pembayaran' => $request->status_pembayaran
        ]);

        // Also sync payment record if exists
        if ($pemesanan->pembayaran) {
            $statusBayar = $request->status_pembayaran === 'lunas' ? 'berhasil' : ($request->status_pembayaran === 'batal' ? 'gagal' : 'menunggu');
            $pemesanan->pembayaran->update(['status' => $statusBayar]);
        }

        return redirect()->route('admin.pemesanan.index')->with('success', "Status pemesanan #{$pemesanan->kode_pemesanan} diubah menjadi " . strtoupper($request->status_pembayaran));
    }

    public function pemesananDestroy($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->delete();
        return redirect()->route('admin.pemesanan.index')->with('success', 'Data pemesanan berhasil dihapus.');
    }

    // --- PEMBAYARAN MANAGEMENT ---
    public function pembayaranIndex()
    {
        $pembayarans = Pembayaran::with(['pemesanan.user', 'pemesanan.mobil.rute'])
            ->latest()
            ->paginate(10);
        return view('admin.pembayaran', compact('pembayarans'));
    }

    public function pembayaranKonfirmasi($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update(['status' => 'berhasil']);

        if ($pembayaran->pemesanan) {
            $pembayaran->pemesanan->update(['status_pembayaran' => 'lunas']);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi dan status pesanan telah menjadi LUNAS!');
    }

    public function pembayaranTolak($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update(['status' => 'gagal']);

        if ($pembayaran->pemesanan) {
            $pembayaran->pemesanan->update(['status_pembayaran' => 'batal']);
        }

        return redirect()->back()->with('success', 'Pembayaran ditolak dan status pesanan diubah menjadi BATAL.');
    }

    // --- PENGGUNA / USER MANAGEMENT ---
    public function penggunaIndex(Request $request)
    {
        $query = User::withCount('pemesanan');

        if ($request->filled('peran')) {
            $query->where('peran', $request->peran);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_lengkap', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('nomor_telepon', 'like', "%{$q}%");
            });
        }

        $penggunas = $query->latest()->paginate(10);
        return view('admin.pengguna', compact('penggunas'));
    }

    public function penggunaStore(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'kata_sandi' => 'required|string|min:6',
            'nomor_telepon' => 'nullable|string|max:20',
            'peran' => 'required|in:admin,pelanggan',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'kata_sandi.required' => 'Kata sandi wajib diisi.',
            'kata_sandi.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'nomor_telepon' => $request->nomor_telepon,
            'peran' => $request->peran,
        ]);

        return redirect()->route('admin.pengguna.index')->with('success', "Pengguna {$user->nama_lengkap} berhasil ditambahkan sebagai " . strtoupper($user->peran));
    }

    public function penggunaUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'nomor_telepon' => 'nullable|string|max:20',
            'peran' => 'required|in:admin,pelanggan',
            'kata_sandi' => 'nullable|string|min:6',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'peran' => $request->peran,
        ];

        if ($request->filled('kata_sandi')) {
            $data['kata_sandi'] = Hash::make($request->kata_sandi);
        }

        $user->update($data);

        return redirect()->route('admin.pengguna.index')->with('success', "Data pengguna {$user->nama_lengkap} berhasil diperbarui.");
    }

    public function penggunaDestroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.pengguna.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan!');
        }

        $user->delete();
        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
