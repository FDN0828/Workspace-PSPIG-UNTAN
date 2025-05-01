<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->string('nama_workspace');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_per_jam', 10, 2);
            $table->integer('kapasitas');
            $table->string('alamat');
            $table->enum('status', ['tersedia', 'tidak_tersedia'])->default('tersedia');
            $table->string('gambar')->nullable();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspaces');
    }
}; 