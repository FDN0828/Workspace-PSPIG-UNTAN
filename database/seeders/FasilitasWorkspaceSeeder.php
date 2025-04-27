<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workspace;
use App\Models\Fasilitas;

class FasilitasWorkspaceSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // nama_workspace => [fasilitas]
            'CW Coffee Tanjung Sari' => ['Ruang Rapat', 'Proyektor', 'Mic', 'Speaker', 'AC'],
            'Lokale Imam Bonjol 04' => ['AC'],
            'Naoto Coffee' => ['AC'],
            'Sahabi Coffee and Eatery' => ['AC'],
            '5 CM Taman Catur' => ['Ruang Rapat', 'Proyektor', 'Mic', 'Speaker', 'AC'],
            'Weng Café' => ['Ruang Rapat', 'Proyektor', 'Mic', 'Speaker', 'Kipas Angin'],
            'RBK' => ['Ruang Rapat', 'Proyektor', 'Mic', 'Speaker', 'AC'],
            'CW Coffee Sepakat 2' => ['Ruang Rapat', 'Proyektor', 'Mic', 'Speaker', 'AC'],
            'Nordu Coffee 2' => ['Ruang Rapat', 'AC'],
        ];
        foreach ($data as $nama => $fasilitasArr) {
            $workspace = Workspace::where('nama_workspace', $nama)->first();
            if ($workspace) {
                $fasilitasIds = Fasilitas::whereIn('nama', $fasilitasArr)->pluck('id')->toArray();
                $workspace->fasilitas()->sync($fasilitasIds);
            }
        }
    }
} 