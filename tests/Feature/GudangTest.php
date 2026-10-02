<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\MonthlyClosing;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GudangTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $kategori;
    protected $satuan;
    protected $sabun;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'admin@vsj.com',
            'role' => 'admin',
        ]);

        $this->kategori = Kategori::create([
            'kode_kategori' => 'KAT-001',
            'nama_kategori' => 'Sabun & Pembersih',
        ]);

        $this->satuan = Satuan::create([
            'nama_satuan' => 'Pcs',
        ]);

        // Create Sabun with initial stock 10
        $this->sabun = Barang::create([
            'kode_barang' => 'SB-001',
            'nama_barang' => 'Sabun Cuci Tangan',
            'kategori_id' => $this->kategori->id,
            'satuan_id' => $this->satuan->id,
            'harga_masuk' => 20000,
            'harga_keluar' => 30000,
            'stok' => 10, // Stock = 10
            'stok_minimal' => 5,
        ]);
    }

    /** @test */
    public function test_barang_keluar_reduces_stock_from_10_to_7_when_sold_3()
    {
        // Initial stock is 10
        $this->assertEquals(10, $this->sabun->stok);

        // Record Barang Keluar (Dijual 3)
        $response = $this->actingAs($this->user)->post(route('transaksi.keluar.store'), [
            'no_surat_jalan' => '47111',
            'tanggal' => now()->format('Y-m-d\TH:i'),
            'ref_no' => 'PO-TEST-001',
            'kirim_ke' => 'Apt. SenRes Bp. Sunoto BD',
            'items' => [
                [
                    'barang_id' => $this->sabun->id,
                    'qty' => 3, // Out 3
                    'bekas_tukar' => 'Jumlah 3',
                    'inventaris_status' => 'Inventaris',
                    'keterangan_kondisi' => 'Kondisi Baik',
                ]
            ]
        ]);

        $response->assertRedirect(route('transaksi.keluar.index'));

        // Refresh stock from database
        $this->sabun->refresh();

        // 10 - 3 = 7
        $this->assertEquals(7, $this->sabun->stok);
    }

    /** @test */
    public function test_surat_jalan_print_page_renders_successfully()
    {
        $bk = BarangKeluar::create([
            'no_surat_jalan' => '47111',
            'tanggal' => now(),
            'kirim_ke' => 'Apt. SenRes Bp. Sunoto BD',
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('transaksi.keluar.print', $bk->id));
        $response->assertStatus(200);
        $response->assertSee('SURAT JALAN');
        $response->assertSee('PT GUDANG KEBERSIHAN VSJ');
        $response->assertSee('47111');
    }

    /** @test */
    public function test_close_monthly_report_freezes_snapshot()
    {
        $response = $this->actingAs($this->user)->post(route('laporan.stok.tutup'), [
            'bulan' => 10,
            'tahun' => 2026,
            'catatan' => 'Tutup laporan akhir bulan Oktober 2026',
        ]);

        $response->assertRedirect(route('laporan.stok', ['bulan' => 10, 'tahun' => 2026]));

        $this->assertDatabaseHas('monthly_closings', [
            'periode' => '2026-10',
            'status' => 'CLOSED',
        ]);
    }
}
