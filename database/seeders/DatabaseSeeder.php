<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rute;
use App\Models\Mobil;
use App\Models\Kursi;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'nama_lengkap' => 'Admin Travel Minang',
            'email' => 'admin@travel.com',
            'kata_sandi' => Hash::make('admin123'),
            'nomor_telepon' => '081266778899',
            'peran' => 'admin',
        ]);
        $admin->createToken('auth_token');

        $pelanggan = User::create([
            'nama_lengkap' => 'Rahmat Minang',
            'email' => 'pelanggan@gmail.com',
            'kata_sandi' => Hash::make('user123'),
            'nomor_telepon' => '089876543210',
            'peran' => 'pelanggan',
        ]);
        $pelanggan->createToken('auth_token');

        $rute1 = Rute::create([
            'kota_asal' => 'Padang',
            'kota_tujuan' => 'Bukittinggi',
            'jarak_km' => 90,
        ]);

        $rute2 = Rute::create([
            'kota_asal' => 'Bukittinggi',
            'kota_tujuan' => 'Payakumbuh',
            'jarak_km' => 35,
        ]);

        $rute3 = Rute::create([
            'kota_asal' => 'Padang',
            'kota_tujuan' => 'Solok',
            'jarak_km' => 60,
        ]);

        $rute4 = Rute::create([
            'kota_asal' => 'Padang',
            'kota_tujuan' => 'Pariaman',
            'jarak_km' => 55,
        ]);

        $rute5 = Rute::create([
            'kota_asal' => 'Bukittinggi',
            'kota_tujuan' => 'Padang Panjang',
            'jarak_km' => 20,
        ]);

        $mobilDaftar = [
            [
                'rute_id' => $rute1->id,
                'nama_mobil' => 'Toyota HiAce Premio (NPM Express Shuttle)',
                'nomor_plat' => 'BA 1234 BKT',
                'jam_keberangkatan' => '07:30 WIB',
                'harga' => 65000,
                'total_kursi' => 10,
                'foto' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=600',
            ],
            [
                'rute_id' => $rute1->id,
                'nama_mobil' => 'Toyota Innova Zenix (Minang Jaya Travel)',
                'nomor_plat' => 'BA 5678 PDG',
                'jam_keberangkatan' => '10:00 WIB',
                'harga' => 75000,
                'total_kursi' => 10,
                'foto' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=600',
            ],
            [
                'rute_id' => $rute1->id,
                'nama_mobil' => 'Mercedes-Benz Sprinter (ANS Travel VIP)',
                'nomor_plat' => 'BA 7777 BA',
                'jam_keberangkatan' => '13:30 WIB',
                'harga' => 85000,
                'total_kursi' => 10,
                'foto' => 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=600',
            ],
            [
                'rute_id' => $rute2->id,
                'nama_mobil' => 'Isuzu Elf Executive (Gumarang Express)',
                'nomor_plat' => 'BA 9012 PYK',
                'jam_keberangkatan' => '08:00 WIB',
                'harga' => 40000,
                'total_kursi' => 10,
                'foto' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600',
            ],
            [
                'rute_id' => $rute3->id,
                'nama_mobil' => 'Toyota HiAce Commuter (Solok Indah Shuttle)',
                'nomor_plat' => 'BA 3456 SLK',
                'jam_keberangkatan' => '09:00 WIB',
                'harga' => 50000,
                'total_kursi' => 10,
                'foto' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=600',
            ],
            [
                'rute_id' => $rute4->id,
                'nama_mobil' => 'Toyota Avanza Veloz (Pariaman Trans)',
                'nomor_plat' => 'BA 2345 PRM',
                'jam_keberangkatan' => '08:30 WIB',
                'harga' => 35000,
                'total_kursi' => 10,
                'foto' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=600',
            ],
        ];

        $baris = ['A', 'B'];

        foreach ($mobilDaftar as $mData) {
            $m = Mobil::create($mData);

            for ($i = 1; $i <= $mData['total_kursi']; $i++) {
                $nomorBaris = ceil($i / 2);
                $huruf = $baris[($i - 1) % 2];
                $nomorKursi = $nomorBaris . $huruf;

                Kursi::create([
                    'mobil_id' => $m->id,
                    'nomor_kursi' => $nomorKursi,
                    'status' => 'tersedia',
                ]);
            }
        }
    }
}
