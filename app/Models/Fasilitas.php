<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;
    protected $table = 'fasilitas';
    protected $fillable = ['nama', 'ikon'];

    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class, 'fasilitas_workspace', 'fasilitas_id', 'workspace_id');
    }
} 