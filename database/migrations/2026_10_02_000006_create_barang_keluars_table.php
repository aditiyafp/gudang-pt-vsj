<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat_jalan')->unique();
            $table->dateTime('tanggal');
            $table->string('ref_no')->nullable(); // Ref.#
            $table->string('kirim_ke'); // Kirim Ke
            $table->integer('halaman')->default(1);
            $table->string('penerima')->nullable(); // Yang Menerima
            $table->string('sopir')->nullable(); // Sopir
            $table->string('petugas_gudang')->nullable(); // Gudang/Petugas
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('barang_keluar_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_keluar_id')->constrained('barang_keluars')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->decimal('qty', 12, 2);
            $table->string('bekas_tukar')->nullable(); // Bekas Jumlah / Tukar
            $table->string('inventaris_status')->nullable(); // Inventaris (+ / -)
            $table->text('keterangan_kondisi')->nullable(); // Keterangan Kondisi Barang Yang Dikembalikan
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_keluar_details');
        Schema::dropIfExists('barang_keluars');
    }
};
