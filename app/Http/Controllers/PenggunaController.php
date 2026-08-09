<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = User::orderBy('id', 'desc')->get();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Daftar pengguna berhasil diambil.',
            'data' => $pengguna
        ], 200);
    }

    public function detail($id)
    {
        $pengguna = User::with('pemesanan')->find($id);

        if (!$pengguna) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'sukses' => true,
            'pesan' => 'Detail pengguna berhasil diambil.',
            'data' => $pengguna
        ], 200);
    }

    public function tambah(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'kata_sandi' => 'required|string|min:6',
            'nomor_telepon' => 'nullable|string|max:20',
            'peran' => 'required|in:admin,pelanggan',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'kata_sandi.required' => 'Kata sandi wajib diisi.',
            'kata_sandi.min' => 'Kata sandi minimal 6 karakter.',
            'peran.required' => 'Peran pengguna wajib dipilih.',
            'peran.in' => 'Peran harus berupa admin atau pelanggan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengguna = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'nomor_telepon' => $request->nomor_telepon,
            'peran' => $request->peran,
        ]);

        return response()->json([
            'sukses' => true,
            'pesan' => 'Pengguna baru berhasil ditambahkan.',
            'data' => $pengguna
        ], 201);
    }

    public function ubah($id, Request $request)
    {
        $pengguna = User::find($id);

        if (!$pengguna) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'kata_sandi' => 'nullable|string|min:6',
            'nomor_telepon' => 'nullable|string|max:20',
            'peran' => 'required|in:admin,pelanggan',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'kata_sandi.min' => 'Kata sandi minimal 6 karakter.',
            'peran.required' => 'Peran pengguna wajib dipilih.',
            'peran.in' => 'Peran harus berupa admin atau pelanggan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $dataUpdate = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'peran' => $request->peran,
        ];

        if ($request->filled('kata_sandi')) {
            $dataUpdate['kata_sandi'] = Hash::make($request->kata_sandi);
        }

        $pengguna->update($dataUpdate);

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data pengguna berhasil diperbarui.',
            'data' => $pengguna
        ], 200);
    }

    public function hapus($id)
    {
        $pengguna = User::find($id);

        if (!$pengguna) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        $pengguna->delete();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Pengguna berhasil dihapus.',
        ], 200);
    }
}
