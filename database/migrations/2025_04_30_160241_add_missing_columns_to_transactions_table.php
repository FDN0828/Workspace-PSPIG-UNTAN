<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan kolom-kolom yang mungkin belum ada di tabel transactions
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'midtrans_payment_id')) {
                $table->string('midtrans_payment_id')->nullable();
            }
            
            if (!Schema::hasColumn('transactions', 'midtrans_payment_url')) {
                $table->string('midtrans_payment_url')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'midtrans_payment_id')) {
                $table->dropColumn('midtrans_payment_id');
            }
            
            if (Schema::hasColumn('transactions', 'midtrans_payment_url')) {
                $table->dropColumn('midtrans_payment_url');
            }
        });
    }
};
