<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id('workspace_id');
            $table->string('nama_workspace');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_per_jam', 10, 2);
            $table->integer('kapasitas');
            $table->string('alamat');
            $table->string('status')->default('tersedia');
            $table->string('gambar')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspaces');
    }
}; 