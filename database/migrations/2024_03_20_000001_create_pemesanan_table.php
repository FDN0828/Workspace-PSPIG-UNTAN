<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->integer('pemesanan_id', true, true)->length(11);
            $table->integer('customer_id')->unsigned();
            $table->integer('workspace_id')->unsigned();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->decimal('total_harga', 10, 2);
            $table->enum('status_pemesanan', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->foreign('customer_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('workspace_id')->references('workspace_id')->on('workspaces')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
}; 