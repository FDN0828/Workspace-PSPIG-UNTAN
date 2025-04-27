<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('ikon')->nullable(); // untuk menyimpan nama ikon fontawesome
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('fasilitas');
    }
}; 