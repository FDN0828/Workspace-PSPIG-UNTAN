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
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('xendit_payment_id', 'midtrans_payment_id')->nullable()->change();
            $table->renameColumn('xendit_payment_url', 'midtrans_payment_url')->nullable()->change();
        });

        Schema::table('pemesanan', function (Blueprint $table) {
            if (!Schema::hasColumn('pemesanan', 'transaction_id')) {
                $table->unsignedBigInteger('transaction_id')->nullable()->after('status_pemesanan');
                $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('midtrans_payment_id', 'xendit_payment_id')->nullable()->change();
            $table->renameColumn('midtrans_payment_url', 'xendit_payment_url')->nullable()->change();
        });

        Schema::table('pemesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pemesanan', 'transaction_id')) {
                $table->dropForeign(['transaction_id']);
                $table->dropColumn('transaction_id');
            }
        });
    }
};
