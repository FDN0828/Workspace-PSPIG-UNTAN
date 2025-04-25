<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Workspace extends Model
{
    use HasFactory;

    protected $primaryKey = 'workspace_id';
    public $incrementing = true;

    protected $fillable = [
        'nama_workspace',
        'deskripsi',
        'harga_per_jam',
        'kapasitas',
        'alamat',
        'status',
        'gambar',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function uploadGambar($gambar)
    {
        if ($gambar) {
            // Hapus gambar lama jika ada
            if ($this->gambar && file_exists(public_path($this->gambar))) {
                unlink(public_path($this->gambar));
            }

            $namaFile = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('images/workspace'), $namaFile);
            
            $this->gambar = 'images/workspace/' . $namaFile;
            $this->save();
        }
    }

    public function getGambarUrl()
    {
        if ($this->gambar && file_exists(public_path($this->gambar))) {
            return asset($this->gambar);
        }
        return asset('images/default-workspace.jpg');
    }
} 