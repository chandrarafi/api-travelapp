<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Kursi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MobilController extends Controller
{
    public function index(Request $request)
    {
        $query = Mobil::with('rute');

        if ($request->has('rute_id')) {
            $query->where('rute_id', $request->rute_id);
        }

        $mobil = $query->get();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Daftar mobil travel berhasil diambil.',
            'data' => $mobil
        ], 200);
    }

    public function detail($id)
    {
        $mobil = Mobil::with(['rute', 'kursi'])->find($id);

        if (!$mobil) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Data mobil tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'sukses' => true,
            'pesan' => 'Detail mobil berhasil diambil.',
            'data' => $mobil
        ], 200);
    }

    public function tambah(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rute_id' => 'required|exists:rute,id',
            'nama_mobil' => 'required|string|max:255',
            'nomor_plat' => 'required|string|max:50',
            'jam_keberangkatan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'total_kursi' => 'nullable|integer|min:1|max:40',
            'foto' => 'nullable',
        ], [
            'rute_id.required' => 'Rute perjalanan wajib dipilih.',
            'rute_id.exists' => 'Rute tidak ditemukan.',
            'nama_mobil.required' => 'Nama mobil wajib diisi.',
            'nomor_plat.required' => 'Nomor plat kendaraan wajib diisi.',
            'jam_keberangkatan.required' => 'Jam keberangkatan wajib diisi.',
            'harga.required' => 'Harga tiket wajib diisi.',
            'harga.numeric' => 'Harga tiket harus berupa angka.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $fotoUrl = 'https://via.placeholder.com/600x400.png?text=Mobil+Travel';

        if ($request->hasFile('foto')) {
            $fileValidator = Validator::make($request->all(), [
                'foto' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            ], [
                'foto.image' => 'File foto harus berupa gambar.',
                'foto.mimes' => 'Format foto harus: jpeg, png, jpg, webp, atau gif.',
                'foto.max' => 'Ukuran foto maksimal 5MB.',
            ]);

            if ($fileValidator->fails()) {
                return response()->json([
                    'sukses' => false,
                    'pesan' => 'Validasi file foto gagal.',
                    'errors' => $fileValidator->errors()
                ], 422);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('mobil', $filename, 'public');
            $fotoUrl = url('storage/' . $path);
        } elseif ($request->filled('foto') && is_string($request->foto)) {
            $fotoUrl = $request->foto;
        }

        $totalKursi = $request->input('total_kursi', 10);

        $mobil = Mobil::create([
            'rute_id' => $request->rute_id,
            'nama_mobil' => $request->nama_mobil,
            'nomor_plat' => $request->nomor_plat,
            'jam_keberangkatan' => $request->jam_keberangkatan,
            'harga' => $request->harga,
            'total_kursi' => $totalKursi,
            'foto' => $fotoUrl,
        ]);

        $baris = ['A', 'B', 'C', 'D'];
        for ($i = 1; $i <= $totalKursi; $i++) {
            $nomorBaris = ceil($i / 2);
            $huruf = $baris[($i - 1) % 2];
            $nomorKursi = $nomorBaris . $huruf;

            Kursi::create([
                'mobil_id' => $mobil->id,
                'nomor_kursi' => $nomorKursi,
                'status' => 'tersedia',
            ]);
        }

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data mobil travel dan kursi berhasil ditambahkan.',
            'data' => Mobil::with('kursi')->find($mobil->id)
        ], 201);
    }

    public function ubah($id, Request $request)
    {
        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Data mobil tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'rute_id' => 'required|exists:rute,id',
            'nama_mobil' => 'required|string|max:255',
            'nomor_plat' => 'required|string|max:50',
            'jam_keberangkatan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'total_kursi' => 'nullable|integer|min:1|max:40',
            'foto' => 'nullable',
        ], [
            'rute_id.required' => 'Rute perjalanan wajib dipilih.',
            'rute_id.exists' => 'Rute tidak ditemukan.',
            'nama_mobil.required' => 'Nama mobil wajib diisi.',
            'nomor_plat.required' => 'Nomor plat kendaraan wajib diisi.',
            'jam_keberangkatan.required' => 'Jam keberangkatan wajib diisi.',
            'harga.required' => 'Harga tiket wajib diisi.',
            'harga.numeric' => 'Harga tiket harus berupa angka.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $fotoUrl = $mobil->foto;

        if ($request->hasFile('foto')) {
            $fileValidator = Validator::make($request->all(), [
                'foto' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            ], [
                'foto.image' => 'File foto harus berupa gambar.',
                'foto.mimes' => 'Format foto harus: jpeg, png, jpg, webp, atau gif.',
                'foto.max' => 'Ukuran foto maksimal 5MB.',
            ]);

            if ($fileValidator->fails()) {
                return response()->json([
                    'sukses' => false,
                    'pesan' => 'Validasi file foto gagal.',
                    'errors' => $fileValidator->errors()
                ], 422);
            }

            if ($mobil->foto && str_contains($mobil->foto, '/storage/mobil/')) {
                $oldPath = str_replace(url('storage/'), '', $mobil->foto);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('mobil', $filename, 'public');
            $fotoUrl = url('storage/' . $path);
        } elseif ($request->filled('foto') && is_string($request->foto)) {
            $fotoUrl = $request->foto;
        }

        $mobil->update([
            'rute_id' => $request->rute_id,
            'nama_mobil' => $request->nama_mobil,
            'nomor_plat' => $request->nomor_plat,
            'jam_keberangkatan' => $request->jam_keberangkatan,
            'harga' => $request->harga,
            'total_kursi' => $request->total_kursi ?? $mobil->total_kursi,
            'foto' => $fotoUrl,
        ]);

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data mobil travel berhasil diperbarui.',
            'data' => Mobil::with(['rute', 'kursi'])->find($mobil->id)
        ], 200);
    }

    public function hapus($id)
    {
        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Data mobil tidak ditemukan.',
            ], 404);
        }

        if ($mobil->foto && str_contains($mobil->foto, '/storage/mobil/')) {
            $oldPath = str_replace(url('storage/'), '', $mobil->foto);
            Storage::disk('public')->delete($oldPath);
        }

        $mobil->delete();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data mobil travel berhasil dihapus.',
        ], 200);
    }
}
