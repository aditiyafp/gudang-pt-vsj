<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_opnames', function (Blueprint $table) {
            $table->id();
            $table->string('no_opname')->unique();
            $table->dateTime('tanggal');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->decimal('stok_sistem', 12, 2);
            $table->decimal('stok_fisik', 12, 2);
            $table->decimal('selisih', 12, 2);
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_opnames');
    }
};
