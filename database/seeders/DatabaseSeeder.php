<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\BarangKeluar;
use App\Models\BarangKeluarDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::create([
            'name' => 'Admin Gudang VSJ',
            'email' => 'admin@vsj.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        $staf = User::create([
            'name' => 'Petugas Gudang',
            'email' => 'gudang@vsj.com',
            'password' => Hash::make('password123'),
            'role' => 'staf_gudang',
            'phone' => '089876543210',
        ]);

        // 2. Seed Kategori
        $katSabun = Kategori::create(['kode_kategori' => 'KAT-001', 'nama_kategori' => 'Sabun & Pembersih', 'keterangan' => 'Cairan pembersih dan sabun']);
        $katAlat = Kategori::create(['kode_kategori' => 'KAT-002', 'nama_kategori' => 'Alat Kebersihan', 'keterangan' => 'Peralatan kebersihan umum']);
        $katPlastic = Kategori::create(['kode_kategori' => 'KAT-003', 'nama_kategori' => 'Plastic & Bag', 'keterangan' => 'Kantong plastik dan pembuangan']);
        $katCloth = Kategori::create(['kode_kategori' => 'KAT-004', 'nama_kategori' => 'Cloth & Mop', 'keterangan' => 'Kain microfiber dan pel']);

        // 3. Seed Satuan
        $satPcs = Satuan::create(['nama_satuan' => 'Pcs', 'keterangan' => 'Pieces']);
        $satBotol = Satuan::create(['nama_satuan' => 'Botol', 'keterangan' => 'Botol cairan']);
        $satPack = Satuan::create(['nama_satuan' => 'Pack', 'keterangan' => 'Paket / Bungkus']);
        $satSet = Satuan::create(['nama_satuan' => 'Set', 'keterangan' => 'Satu set']);

        // 4. Seed Supplier
        $sup1 = Supplier::create([
            'kode_supplier' => 'SUP-001',
            'nama_supplier' => 'PT Chemindo Kebersihan Utama',
            'telepon' => '021-5551234',
            'email' => 'sales@chemindo.co.id',
            'alamat' => 'Jl. Industri Kebersihan No. 45, Jakarta',
        ]);

        $sup2 = Supplier::create([
            'kode_supplier' => 'SUP-002',
            'nama_supplier' => 'CV Suplier Alat Kebersihan VSJ',
            'telepon' => '021-7778899',
            'email' => 'info@vsj-clean.com',
            'alamat' => 'Jl. Raya VSJ No. 12, Bekasi',
        ]);

        // 5. Seed Barang (with exact items like in the prompt & photo!)
        $sabunHand = Barang::create([
            'kode_barang' => 'SB-001',
            'nama_barang' => 'Sabun Cuci Tangan Liquid (Hand Soap)',
            'kategori_id' => $katSabun->id,
            'satuan_id' => $satBotol->id,
            'harga_masuk' => 25000,
            'harga_keluar' => 35000,
            'stok' => 7, // Initial example matching prompt: 10 entered/out, sold 3 = 7 remaining!
            'stok_minimal' => 5,
            'lokasi_rak' => 'Rak A-01',
            'keterangan' => 'Sabun cuci tangan 500ml',
        ]);

        $sabunFloor = Barang::create([
            'kode_barang' => 'SB-002',
            'nama_barang' => 'Sabun Pembersih Lantai (Floor Cleaner)',
            'kategori_id' => $katSabun->id,
            'satuan_id' => $satBotol->id,
            'harga_masuk' => 30000,
            'harga_keluar' => 45000,
            'stok' => 15,
            'stok_minimal' => 5,
            'lokasi_rak' => 'Rak A-02',
            'keterangan' => 'Cairan pembersih lantai 1 Litre',
        ]);

        $itemA05 = Barang::create([
            'kode_barang' => 'A05',
            'nama_barang' => 'Rep Rubberwindowsqueegee35cmNew',
            'kategori_id' => $katAlat->id,
            'satuan_id' => $satPcs->id,
            'harga_masuk' => 45000,
            'harga_keluar' => 65000,
            'stok' => 20,
            'stok_minimal' => 5,
            'lokasi_rak' => 'Rak B-01',
        ]);

        $itemA06 = Barang::create([
            'kode_barang' => 'A06',
            'nama_barang' => 'Rep Rubber window squeegee 45cm',
            'kategori_id' => $katAlat->id,
            'satuan_id' => $satPcs->id,
            'harga_masuk' => 50000,
            'harga_keluar' => 75000,
            'stok' => 18,
            'stok_minimal' => 5,
            'lokasi_rak' => 'Rak B-02',
        ]);

        $itemF01F = Barang::create([
            'kode_barang' => 'F01F',
            'nama_barang' => 'Glass Cleaning cloth 34 x 21cm OSK',
            'kategori_id' => $katCloth->id,
            'satuan_id' => $satPcs->id,
            'harga_masuk' => 12000,
            'harga_keluar' => 20000,
            'stok' => 50,
            'stok_minimal' => 10,
            'lokasi_rak' => 'Rak C-01',
        ]);

        $itemJ03 = Barang::create([
            'kode_barang' => 'J03',
            'nama_barang' => 'Plastic bag black size 60 x 70cm',
            'kategori_id' => $katPlastic->id,
            'satuan_id' => $satPack->id,
            'harga_masuk' => 15000,
            'harga_keluar' => 25000,
            'stok' => 100,
            'stok_minimal' => 20,
            'lokasi_rak' => 'Rak D-01',
        ]);

        // 6. Seed Barang Masuk Transaction (Initial Sabun 10)
        $bm1 = BarangMasuk::create([
            'no_transaksi' => 'BM-202610-0001',
            'tanggal' => Carbon::now()->subDays(5),
            'supplier_id' => $sup1->id,
            'user_id' => $admin->id,
            'catatan' => 'Penerimaan stok sabun & alat kebersihan awal bulan',
        ]);

        BarangMasukDetail::create([
            'barang_masuk_id' => $bm1->id,
            'barang_id' => $sabunHand->id,
            'qty' => 10, // Stock entered = 10
            'harga_satuan' => 25000,
            'subtotal' => 250000,
        ]);

        // 7. Seed Barang Keluar Transaction (Surat Jalan 47111 - Example matching prompt: Sabun keluar 3 -> sisa 7!)
        $bk1 = BarangKeluar::create([
            'no_surat_jalan' => '47111',
            'tanggal' => Carbon::now()->subDays(2),
            'ref_no' => 'PO-VSJ-2023-089',
            'kirim_ke' => 'Apt. SenRes Bp. Sunoto BD',
            'halaman' => 1,
            'penerima' => 'Bp. Sunoto',
            'sopir' => 'Budi (Sopir VSJ)',
            'petugas_gudang' => 'Admin Gudang VSJ',
            'user_id' => $admin->id,
            'catatan' => 'Pengiriman Surat Jalan Barang Keluar Operasional',
        ]);

        BarangKeluarDetail::create([
            'barang_keluar_id' => $bk1->id,
            'barang_id' => $sabunHand->id,
            'qty' => 3, // Sold/issued 3 Pcs -> 10 - 3 = 7 remaining!
            'bekas_tukar' => 'Jumlah 3',
            'inventaris_status' => 'Inventaris',
            'keterangan_kondisi' => 'Kondisi Baik & Tersegel',
            'harga_satuan' => 35000,
            'subtotal' => 105000,
        ]);

        BarangKeluarDetail::create([
            'barang_keluar_id' => $bk1->id,
            'barang_id' => $itemA05->id,
            'qty' => 2,
            'bekas_tukar' => 'Bekas 2',
            'inventaris_status' => '+',
            'keterangan_kondisi' => 'Kondisi Dikembalikan Lengkap',
            'harga_satuan' => 65000,
            'subtotal' => 130000,
        ]);
    }
}
