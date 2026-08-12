<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rute;
use App\Models\Mobil;
use App\Models\Kursi;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        $pelanggan1 = User::create([
            'nama_lengkap' => 'Rahmat Minang',
            'email' => 'pelanggan@gmail.com',
            'kata_sandi' => Hash::make('user123'),
            'nomor_telepon' => '089876543210',
            'peran' => 'pelanggan',
        ]);
        $pelanggan1->createToken('auth_token');

        $pelanggan2 = User::create([
            'nama_lengkap' => 'Siti Nurhaliza',
            'email' => 'siti@gmail.com',
            'kata_sandi' => Hash::make('user123'),
            'nomor_telepon' => '081399887766',
            'peran' => 'pelanggan',
        ]);

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
        $createdMobils = [];

        foreach ($mobilDaftar as $mData) {
            $m = Mobil::create($mData);
            $createdMobils[] = $m;

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

        // Create sample bookings & payments
        $m1 = $createdMobils[0];
        $p1 = Pemesanan::create([
            'user_id' => $pelanggan1->id,
            'mobil_id' => $m1->id,
            'kode_pemesanan' => 'TRV' . strtoupper(Str::random(6)),
            'tanggal_keberangkatan' => now()->addDays(2)->format('Y-m-d'),
            'jumlah_kursi' => 2,
            'total_bayar' => $m1->harga * 2,
            'status_pembayaran' => 'lunas',
            'metode_pembayaran' => 'Transfer BCA',
        ]);

        Pembayaran::create([
            'pemesanan_id' => $p1->id,
            'metode_pembayaran' => 'Transfer BCA',
            'jumlah_bayar' => $p1->total_bayar,
            'bukti_pembayaran' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=600',
            'status' => 'berhasil',
        ]);

        $m2 = $createdMobils[1];
        $p2 = Pemesanan::create([
            'user_id' => $pelanggan2->id,
            'mobil_id' => $m2->id,
            'kode_pemesanan' => 'TRV' . strtoupper(Str::random(6)),
            'tanggal_keberangkatan' => now()->addDays(3)->format('Y-m-d'),
            'jumlah_kursi' => 1,
            'total_bayar' => $m2->harga,
            'status_pembayaran' => 'pending',
            'metode_pembayaran' => 'Transfer Mandiri',
        ]);

        Pembayaran::create([
            'pemesanan_id' => $p2->id,
            'metode_pembayaran' => 'Transfer Mandiri',
            'jumlah_bayar' => $p2->total_bayar,
            'bukti_pembayaran' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=600',
            'status' => 'menunggu',
        ]);
    }
}
