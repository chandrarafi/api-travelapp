<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OtentikasiController extends Controller
{
    public function registrasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'kata_sandi' => 'required|string|min:6',
            'nomor_telepon' => 'nullable|string|max:20',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'kata_sandi.required' => 'Kata sandi wajib diisi.',
            'kata_sandi.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'nomor_telepon' => $request->nomor_telepon,
            'peran' => 'pelanggan',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'sukses' => true,
            'pesan' => 'Registrasi pelanggan berhasil.',
            'data' => [
                'token' => $token,
                'pengguna' => [
                    'id' => $user->id,
                    'nama_lengkap' => $user->nama_lengkap,
                    'email' => $user->email,
                    'nomor_telepon' => $user->nomor_telepon,
                    'peran' => $user->peran,
                ]
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'kata_sandi' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'kata_sandi.required' => 'Kata sandi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->kata_sandi, $user->kata_sandi)) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Email atau kata sandi salah.',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'sukses' => true,
            'pesan' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'pengguna' => [
                    'id' => $user->id,
                    'nama_lengkap' => $user->nama_lengkap,
                    'email' => $user->email,
                    'nomor_telepon' => $user->nomor_telepon,
                    'peran' => $user->peran,
                ]
            ]
        ], 200);
    }

    public function profil(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data profil berhasil diambil.',
            'data' => [
                'id' => $user->id,
                'nama_lengkap' => $user->nama_lengkap,
                'email' => $user->email,
                'nomor_telepon' => $user->nomor_telepon,
                'peran' => $user->peran,
            ]
        ], 200);
    }

    public function keluar(Request $request)
    {
        $user = $request->user();
        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'sukses' => true,
            'pesan' => 'Logout berhasil.',
        ], 200);
    }
}
