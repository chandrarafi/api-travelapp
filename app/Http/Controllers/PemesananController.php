<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Mobil;
use App\Models\Kursi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PemesananController extends Controller
{
    public function buatPemesanan(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'mobil_id' => 'required|exists:mobil,id',
            'tanggal_keberangkatan' => 'required|date|after_or_equal:today',
            'kursi_ids' => 'required|array|min:1',
            'kursi_ids.*' => 'exists:kursi,id',
            'metode_pembayaran' => 'nullable|string',
        ], [
            'mobil_id.required' => 'Mobil travel wajib dipilih.',
            'mobil_id.exists' => 'Data mobil travel tidak ditemukan.',
            'tanggal_keberangkatan.required' => 'Tanggal keberangkatan wajib diisi.',
            'tanggal_keberangkatan.date' => 'Format tanggal keberangkatan tidak valid.',
            'tanggal_keberangkatan.after_or_equal' => 'Tanggal keberangkatan tidak boleh tanggal yang sudah lewat.',
            'kursi_ids.required' => 'Pilih minimal 1 kursi.',
            'kursi_ids.array' => 'Daftar kursi harus berupa array.',
            'kursi_ids.min' => 'Pilih minimal 1 kursi.',
            'kursi_ids.*.exists' => 'Salah satu kursi yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $mobil = Mobil::find($request->mobil_id);
        $kursiIds = $request->kursi_ids;

        $kursiDaftar = Kursi::whereIn('id', $kursiIds)->where('mobil_id', $mobil->id)->get();

        if ($kursiDaftar->count() !== count($kursiIds)) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Beberapa kursi yang dipilih tidak sesuai dengan mobil travel ini.',
            ], 400);
        }

        foreach ($kursiDaftar as $k) {
            if ($k->status === 'terpesan') {
                return response()->json([
                    'sukses' => false,
                    'pesan' => 'Kursi ' . $k->nomor_kursi . ' sudah terpesan oleh pelanggan lain.',
                ], 400);
            }
        }

        DB::beginTransaction();
        try {
            $kodePemesanan = 'TRV-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $jumlahKursi = count($kursiIds);
            $totalBayar = $mobil->harga * $jumlahKursi;

            $pemesanan = Pemesanan::create([
                'user_id' => $user->id,
                'mobil_id' => $mobil->id,
                'kode_pemesanan' => $kodePemesanan,
                'tanggal_keberangkatan' => $request->tanggal_keberangkatan,
                'jumlah_kursi' => $jumlahKursi,
                'total_bayar' => $totalBayar,
                'status_pembayaran' => 'pending',
                'metode_pembayaran' => $request->metode_pembayaran ?? 'Transfer Bank',
            ]);

            foreach ($kursiDaftar as $k) {
                DetailPemesanan::create([
                    'pemesanan_id' => $pemesanan->id,
                    'kursi_id' => $k->id,
                    'harga_kursi' => $mobil->harga,
                ]);

                $k->status = 'terpesan';
                $k->save();
            }

            DB::commit();

            $pemesananLengkap = Pemesanan::with(['mobil.rute', 'detailPemesanan.kursi', 'user'])->find($pemesanan->id);

            return response()->json([
                'sukses' => true,
                'pesan' => 'Pemesanan tiket mobil travel berhasil dibuat. Silakan lakukan pembayaran.',
                'data' => $pemesananLengkap
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'sukses' => false,
                'pesan' => 'Terjadi kesalahan saat memproses pemesanan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $query = Pemesanan::with(['mobil.rute', 'detailPemesanan.kursi', 'user', 'pembayaran']);

        if ($user->peran !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $pemesanan = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Daftar pemesanan berhasil diambil.',
            'data' => $pemesanan
        ], 200);
    }

    public function detail($id, Request $request)
    {
        $user = $request->user();

        $pemesanan = Pemesanan::with(['mobil.rute', 'detailPemesanan.kursi', 'user', 'pembayaran'])->find($id);

        if (!$pemesanan) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Data pemesanan tidak ditemukan.',
            ], 404);
        }

        if ($user->peran !== 'admin' && $pemesanan->user_id !== $user->id) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Akses ditolak. Anda tidak memiliki akses ke pemesanan ini.',
            ], 403);
        }

        return response()->json([
            'sukses' => true,
            'pesan' => 'Detail pemesanan berhasil diambil.',
            'data' => $pemesanan
        ], 200);
    }

    public function ubahStatus($id, Request $request)
    {
        $pemesanan = Pemesanan::find($id);

        if (!$pemesanan) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Data pemesanan tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status_pembayaran' => 'required|in:pending,lunas,batal',
        ], [
            'status_pembayaran.required' => 'Status pembayaran wajib diisi.',
            'status_pembayaran.in' => 'Status pembayaran harus berupa: pending, lunas, atau batal.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $pemesanan->status_pembayaran = $request->status_pembayaran;
            $pemesanan->save();

            if ($request->status_pembayaran === 'batal') {
                $detailList = DetailPemesanan::where('pemesanan_id', $pemesanan->id)->get();
                foreach ($detailList as $d) {
                    Kursi::where('id', $d->kursi_id)->update(['status' => 'tersedia']);
                }
            }

            DB::commit();

            return response()->json([
                'sukses' => true,
                'pesan' => 'Status pemesanan berhasil diperbarui menjadi ' . $request->status_pembayaran . '.',
                'data' => Pemesanan::with(['mobil.rute', 'detailPemesanan.kursi', 'user'])->find($pemesanan->id)
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'sukses' => false,
                'pesan' => 'Gagal mengubah status pemesanan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function hapus($id)
    {
        $pemesanan = Pemesanan::find($id);

        if (!$pemesanan) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Data pemesanan tidak ditemukan.',
            ], 404);
        }

        $detailList = DetailPemesanan::where('pemesanan_id', $pemesanan->id)->get();
        foreach ($detailList as $d) {
            Kursi::where('id', $d->kursi_id)->update(['status' => 'tersedia']);
        }

        $pemesanan->delete();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data pemesanan berhasil dihapus.',
        ], 200);
    }
}
