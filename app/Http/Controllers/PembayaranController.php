<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\DetailPemesanan;
use App\Models\Kursi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function bayar($id, Request $request)
    {
        $user = $request->user();

        $pemesanan = Pemesanan::find($id);

        if (!$pemesanan) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Data pemesanan tidak ditemukan.',
            ], 404);
        }

        if ($user->peran !== 'admin' && $pemesanan->user_id !== $user->id) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Akses ditolak. Anda tidak berhak melakukan pembayaran pemesanan ini.',
            ], 403);
        }

        if ($pemesanan->status_pembayaran === 'lunas') {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Pemesanan ini sudah lunas dibayar.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'metode_pembayaran' => 'required|string|max:100',
            'jumlah_bayar' => 'required|numeric|min:' . $pemesanan->total_bayar,
            'bukti_pembayaran' => 'nullable',
        ], [
            'metode_pembayaran.required' => 'Metode pembayaran wajib diisi.',
            'jumlah_bayar.required' => 'Jumlah bayar wajib diisi.',
            'jumlah_bayar.numeric' => 'Jumlah bayar harus berupa angka.',
            'jumlah_bayar.min' => 'Jumlah bayar kurang dari total tagihan (' . number_format($pemesanan->total_bayar, 0, ',', '.') . ').',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $buktiUrl = null;

        if ($request->hasFile('bukti_pembayaran')) {
            $fileValidator = Validator::make($request->all(), [
                'bukti_pembayaran' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            ], [
                'bukti_pembayaran.image' => 'Bukti pembayaran harus berupa gambar.',
                'bukti_pembayaran.mimes' => 'Format gambar bukti pembayaran harus: jpeg, png, jpg, webp, atau gif.',
                'bukti_pembayaran.max' => 'Ukuran file bukti pembayaran maksimal 5MB.',
            ]);

            if ($fileValidator->fails()) {
                return response()->json([
                    'sukses' => false,
                    'pesan' => 'Validasi file bukti pembayaran gagal.',
                    'errors' => $fileValidator->errors()
                ], 422);
            }

            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pembayaran', $filename, 'public');
            $buktiUrl = url('storage/' . $path);
        } elseif ($request->filled('bukti_pembayaran') && is_string($request->bukti_pembayaran)) {
            $buktiUrl = $request->bukti_pembayaran;
        }

        DB::beginTransaction();
        try {
            $pembayaran = Pembayaran::updateOrCreate(
                ['pemesanan_id' => $pemesanan->id],
                [
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'jumlah_bayar' => $request->jumlah_bayar,
                    'bukti_pembayaran' => $buktiUrl,
                    'status' => 'menunggu',
                    'tanggal_pembayaran' => now(),
                ]
            );

            $pemesanan->status_pembayaran = 'menunggu_konfirmasi';
            $pemesanan->metode_pembayaran = $request->metode_pembayaran;
            $pemesanan->save();

            DB::commit();

            return response()->json([
                'sukses' => true,
                'pesan' => 'Bukti pembayaran berhasil diunggah. Silakan tunggu konfirmasi dari Admin.',
                'data' => [
                    'pemesanan' => Pemesanan::with(['mobil.rute', 'detailPemesanan.kursi'])->find($pemesanan->id),
                    'pembayaran' => $pembayaran,
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'sukses' => false,
                'pesan' => 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function konfirmasiAdmin($id, Request $request)
    {
        $pemesanan = Pemesanan::find($id);

        if (!$pemesanan) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Data pemesanan tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:lunas,batal',
        ], [
            'status.required' => 'Status konfirmasi wajib diisi.',
            'status.in' => 'Status konfirmasi harus berupa: lunas atau batal.',
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
            $statusKonfirmasi = $request->status;

            if ($statusKonfirmasi === 'lunas') {
                $pemesanan->status_pembayaran = 'lunas';
                $pemesanan->save();

                Pembayaran::where('pemesanan_id', $pemesanan->id)->update([
                    'status' => 'berhasil',
                ]);

                $pesanOutput = 'Pembayaran berhasil dikonfirmasi LUNAS oleh Admin. Tiket dapat digunakan.';
            } else {
                $pemesanan->status_pembayaran = 'batal';
                $pemesanan->save();

                Pembayaran::where('pemesanan_id', $pemesanan->id)->update([
                    'status' => 'gagal',
                ]);

                $detailList = DetailPemesanan::where('pemesanan_id', $pemesanan->id)->get();
                foreach ($detailList as $d) {
                    Kursi::where('id', $d->kursi_id)->update(['status' => 'tersedia']);
                }

                $pesanOutput = 'Pembayaran DITOLAK / DIBATALKAN oleh Admin. Kursi telah dibebaskan.';
            }

            DB::commit();

            return response()->json([
                'sukses' => true,
                'pesan' => $pesanOutput,
                'data' => Pemesanan::with(['mobil.rute', 'detailPemesanan.kursi', 'user', 'pembayaran'])->find($pemesanan->id)
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'sukses' => false,
                'pesan' => 'Gagal memproses konfirmasi pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }
}
