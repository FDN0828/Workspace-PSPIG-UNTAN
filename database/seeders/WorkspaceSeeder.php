<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workspace;
use App\Models\User;

class WorkspaceSeeder extends Seeder
{
    public function run()
    {
        $userId = User::first()->user_id ?? 1;
        $workspaces = [
            [
                'nama_workspace' => 'CW Coffee Tanjung Sari',
                'alamat' => 'Jalan Tanjung Sari',
                'kapasitas' => 5,
                'deskripsi' => '4 - 5 orang',
                'status' => 'tersedia',
                'harga_per_jam' => 0,
                'user_id' => 2,
            ],
            [
                'nama_workspace' => 'Lokale Imam Bonjol 04',
                'alamat' => 'Jalan Imam Bonjol',
                'kapasitas' => 5,
                'deskripsi' => '4 - 5 orang',
                'status' => 'tersedia',
                'harga_per_jam' => 0,
                'user_id' => 3,
            ],
            [
                'nama_workspace' => 'Naoto Coffee',
                'alamat' => 'Jalan Sepakat 1',
                'kapasitas' => 5,
                'deskripsi' => '4 - 5 orang',
                'status' => 'tersedia',
                'harga_per_jam' => 0,
                'user_id' => 4,
            ],
            [
                'nama_workspace' => 'Sahabi Coffee and Eatery',
                'alamat' => 'Jalan Sepakat 1',
                'kapasitas' => 5,
                'deskripsi' => '4 - 5 orang',
                'status' => 'tersedia',
                'harga_per_jam' => 0,
                'user_id' => 5,
            ],
            [
                'nama_workspace' => '5 CM Taman Catur',
                'alamat' => 'Jalan Reformasi Untan',
                'kapasitas' => 6,
                'deskripsi' => '5 - 6 orang',
                'status' => 'tersedia',
                'harga_per_jam' => 0,
                'user_id' => 6,
            ],
            [
                'nama_workspace' => 'Weng Café',
                'alamat' => 'Jalan Reformasi Untan',
                'kapasitas' => 5,
                'deskripsi' => '4 - 5 orang',
                'status' => 'tersedia',
                'harga_per_jam' => 0,
                'user_id' => 7,
            ],
            [
                'nama_workspace' => 'RBK',
                'alamat' => 'Jalan Reformasi Untan',
                'kapasitas' => 5,
                'deskripsi' => '4 - 5 orang',
                'status' => 'tersedia',
                'harga_per_jam' => 0,
                'user_id' => 8,
            ],
            [
                'nama_workspace' => 'CW Coffee Sepakat 2',
                'alamat' => 'Jalan Sepakat 2',
                'kapasitas' => 6,
                'deskripsi' => '5 - 6 orang',
                'status' => 'tersedia',
                'harga_per_jam' => 0,
                'user_id' => 9,
            ],
            [
                'nama_workspace' => 'Nordu Coffee 2',
                'alamat' => 'Jalan Sepakat 2',
                'kapasitas' => 4,
                'deskripsi' => '3 - 4 orang',
                'status' => 'tersedia',
                'harga_per_jam' => 0,
                'user_id' => 10,
            ],
        ];
        foreach ($workspaces as $w) {
            Workspace::firstOrCreate([
                'nama_workspace' => $w['nama_workspace'],
                'alamat' => $w['alamat'],
            ], $w);
        }
    }
} 