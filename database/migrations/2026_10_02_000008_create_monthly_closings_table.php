<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_closings', function (Blueprint $table) {
            $table->id();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->string('periode')->unique(); // e.g. "2026-10"
            $table->dateTime('tanggal_tutup');
            $table->foreignId('closed_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['OPEN', 'CLOSED'])->default('CLOSED');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('monthly_closing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monthly_closing_id')->constrained('monthly_closings')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->decimal('stok_awal', 12, 2)->default(0);
            $table->decimal('total_masuk', 12, 2)->default(0);
            $table->decimal('total_keluar', 12, 2)->default(0);
            $table->decimal('stok_akhir', 12, 2)->default(0);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('total_nilai', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_closing_details');
        Schema::dropIfExists('monthly_closings');
    }
};
