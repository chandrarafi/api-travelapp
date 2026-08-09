<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rute;
use Illuminate\Support\Facades\Validator;

class RuteController extends Controller
{
    public function index()
    {
        $rute = Rute::all();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Daftar rute berhasil diambil.',
            'data' => $rute
        ], 200);
    }

    public function detail($id)
    {
        $rute = Rute::with('mobil')->find($id);

        if (!$rute) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Rute tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'sukses' => true,
            'pesan' => 'Detail rute berhasil diambil.',
            'data' => $rute
        ], 200);
    }

    public function tambah(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kota_asal' => 'required|string|max:255',
            'kota_tujuan' => 'required|string|max:255',
            'jarak_km' => 'nullable|integer',
        ], [
            'kota_asal.required' => 'Kota asal wajib diisi.',
            'kota_tujuan.required' => 'Kota tujuan wajib diisi.',
            'jarak_km.integer' => 'Jarak harus berupa angka.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $rute = Rute::create([
            'kota_asal' => $request->kota_asal,
            'kota_tujuan' => $request->kota_tujuan,
            'jarak_km' => $request->jarak_km,
        ]);

        return response()->json([
            'sukses' => true,
            'pesan' => 'Rute baru berhasil ditambahkan.',
            'data' => $rute
        ], 201);
    }

    public function ubah($id, Request $request)
    {
        $rute = Rute::find($id);

        if (!$rute) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Rute tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'kota_asal' => 'required|string|max:255',
            'kota_tujuan' => 'required|string|max:255',
            'jarak_km' => 'nullable|integer',
        ], [
            'kota_asal.required' => 'Kota asal wajib diisi.',
            'kota_tujuan.required' => 'Kota tujuan wajib diisi.',
            'jarak_km.integer' => 'Jarak harus berupa angka.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $rute->update([
            'kota_asal' => $request->kota_asal,
            'kota_tujuan' => $request->kota_tujuan,
            'jarak_km' => $request->jarak_km,
        ]);

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data rute berhasil diperbarui.',
            'data' => $rute
        ], 200);
    }

    public function hapus($id)
    {
        $rute = Rute::find($id);

        if (!$rute) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Rute tidak ditemukan.',
            ], 404);
        }

        $rute->delete();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Rute berhasil dihapus.',
        ], 200);
    }
}
