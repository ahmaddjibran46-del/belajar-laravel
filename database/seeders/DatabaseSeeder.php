<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun kasir default
        User::create([
            'name' => 'Admin Kasir',
            'email' => 'kasir@resto.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Meja 1 - 10
        for ($i = 1; $i <= 10; $i++) {
            Meja::create([
                'nomor_meja' => str_pad($i, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // Kategori & menu contoh
        $mie = Kategori::create(['nama' => 'Mie', 'urutan' => 1]);
        $dimsum = Kategori::create(['nama' => 'Dimsum', 'urutan' => 2]);
        $minuman = Kategori::create(['nama' => 'Minuman', 'urutan' => 3]);

        Menu::insert([
            ['kategori_id' => $mie->id, 'nama' => 'Mie Setan', 'deskripsi' => 'Mie pedas level bebas', 'harga' => 11000, 'status' => 'tersedia', 'created_at' => now(), 'updated_at' => now()],
            ['kategori_id' => $mie->id, 'nama' => 'Mie Iblis', 'deskripsi' => 'Mie pedas dengan kuah', 'harga' => 11000, 'status' => 'tersedia', 'created_at' => now(), 'updated_at' => now()],
            ['kategori_id' => $mie->id, 'nama' => 'Mie Angel', 'deskripsi' => 'Mie tanpa pedas', 'harga' => 11000, 'status' => 'tersedia', 'created_at' => now(), 'updated_at' => now()],
            ['kategori_id' => $dimsum->id, 'nama' => 'Dimsum Ayam', 'deskripsi' => 'Isi 5 pcs', 'harga' => 12000, 'status' => 'tersedia', 'created_at' => now(), 'updated_at' => now()],
            ['kategori_id' => $dimsum->id, 'nama' => 'Pangsit Goreng', 'deskripsi' => 'Isi 5 pcs', 'harga' => 12000, 'status' => 'tersedia', 'created_at' => now(), 'updated_at' => now()],
            ['kategori_id' => $minuman->id, 'nama' => 'Es Teh', 'deskripsi' => null, 'harga' => 5000, 'status' => 'tersedia', 'created_at' => now(), 'updated_at' => now()],
            ['kategori_id' => $minuman->id, 'nama' => 'Es Jeruk', 'deskripsi' => null, 'harga' => 6000, 'status' => 'tersedia', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
