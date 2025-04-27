<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fasilitas;

class FasilitasSeeder extends Seeder
{
    public function run()
    {
        $fasilitas = [
            ['nama' => 'Ruang Rapat', 'ikon' => 'fa-door-closed'],
            ['nama' => 'Proyektor', 'ikon' => 'fa-video'],
            ['nama' => 'Mic', 'ikon' => 'fa-microphone'],
            ['nama' => 'Speaker', 'ikon' => 'fa-volume-up'],
            ['nama' => 'AC', 'ikon' => 'fa-snowflake'],
            ['nama' => 'Kipas Angin', 'ikon' => 'fa-fan'],
        ];
        foreach ($fasilitas as $f) {
            Fasilitas::firstOrCreate(['nama' => $f['nama']], $f);
        }
    }
} 