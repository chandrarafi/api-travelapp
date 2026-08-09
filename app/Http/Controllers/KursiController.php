<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kursi;
use App\Models\Mobil;

class KursiController extends Controller
{
    public function index($mobilId)
    {
        $mobil = Mobil::find($mobilId);

        if (!$mobil) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Data mobil tidak ditemukan.',
            ], 404);
        }

        $kursi = Kursi::where('mobil_id', $mobilId)->get();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Daftar kursi berhasil diambil.',
            'mobil' => [
                'id' => $mobil->id,
                'nama_mobil' => $mobil->nama_mobil,
                'jam_keberangkatan' => $mobil->jam_keberangkatan,
                'harga' => $mobil->harga,
                'foto' => $mobil->foto,
            ],
            'data' => $kursi
        ], 200);
    }
}
