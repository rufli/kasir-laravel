<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Pengeluaran;
use App\Models\TransaksiPenjualan;
use App\Models\KategoriPengeluaran;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        // Jalankan seeder biar user admin ada
        $this->seed();

        // Ambil user admin dari seeder
        $this->user = User::where('username', 'admin')->first();

        // Login otomatis sebelum setiap test
        $this->actingAs($this->user);

        // Buat kategori pengeluaran supaya ID valid
        $this->kategori = \App\Models\KategoriPengeluaran::first();
    }

    #[Test]
    public function menampilkan_dashboard_dengan_total_benar()
    {
        $today = Carbon::today();

        TransaksiPenjualan::create([
            'users_id' => $this->user->id,
            'tanggal' => $today->format('Y-m-d'),
            'total_harga' => 200000,
            'metode_pembayaran' => 'tunai',
            'jumlah_dibayar' => 200000,
            'jumlah_kembalian' => 0,
        ]);

        Pengeluaran::create([
            'tanggal' => $today->format('Y-m-d'),
            'nama' => 'Beli ATK',
            'jumlah' => 50000,
            'catatan' => 'Pembelian alat tulis kantor',
            'kategori_pengeluaran_id' => $this->kategori->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->get(route('dashboard.index'));

        $response->assertStatus(200);
        $response->assertViewHas('totalPenjualanBulan', 200000);
        $response->assertViewHas('totalPengeluaranBulan', 50000);
        $response->assertViewHas('labaBulan', 150000);
        $response->assertViewHas('hariTerakhir');
    }

    #[Test]
    public function mengembalikan_json_data_7_hari_terakhir()
    {
        // generate data penjualan dan pengeluaran 7 hari ke belakang
        for ($i = 0; $i < 7; $i++) {
            TransaksiPenjualan::factory()->create([
                'tanggal' => Carbon::now()->subDays($i),
            ]);

            Pengeluaran::factory()->create([
                'tanggal' => Carbon::now()->subDays($i),
            ]);
        }

        $response = $this->getJson('/dashboard/data?range=7')
            ->assertStatus(200)
            ->json();

        $this->assertArrayHasKey('labels', $response);
        $this->assertArrayHasKey('penjualan', $response);
        $this->assertArrayHasKey('pengeluaran', $response);

        $this->assertCount(7, $response['labels']);
        $this->assertCount(7, $response['penjualan']);
        $this->assertCount(7, $response['pengeluaran']);
    }

    #[Test]
    public function mengembalikan_json_data_30_hari_terakhir()
    {
        // generate data penjualan dan pengeluaran 30 hari ke belakang
        for ($i = 0; $i < 30; $i++) {
            TransaksiPenjualan::factory()->create([
                'tanggal' => Carbon::now()->subDays($i),
            ]);

            Pengeluaran::factory()->create([
                'tanggal' => Carbon::now()->subDays($i),
            ]);
        }

        $response = $this->getJson('/dashboard/data?range=30')
            ->assertStatus(200)
            ->json();

        $this->assertArrayHasKey('labels', $response);
        $this->assertArrayHasKey('penjualan', $response);
        $this->assertArrayHasKey('pengeluaran', $response);

        $this->assertCount(30, $response['labels']);
        $this->assertCount(30, $response['penjualan']);
        $this->assertCount(30, $response['pengeluaran']);
    }

    #[Test]
    public function mengembalikan_json_data_12_bulan_terakhir()
    {
        // Buat data dummy 12 bulan terakhir (tanggal 1 tiap bulan)
        for ($i = 11; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subMonths($i)->startOfMonth()->format('Y-m-d');

            TransaksiPenjualan::create([
                'users_id' => $this->user->id,
                'tanggal' => $tanggal,
                'total_harga' => 300000,
                'metode_pembayaran' => 'tunai',
                'jumlah_dibayar' => 300000,
                'jumlah_kembalian' => 0,
            ]);

            Pengeluaran::create([
                'tanggal' => $tanggal,
                'nama' => 'Pengeluaran ' . $i,
                'jumlah' => 100000,
                'catatan' => 'Catatan ' . $i,
                'user_id' => $this->user->id,
                'kategori_pengeluaran_id' => $this->kategori->id,
            ]);
        }

        $response = $this->getJson(route('dashboard.data', ['periode' => 365]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'labels',
                'penjualan',
                'pengeluaran'
            ]);

        $this->assertCount(12, $response['labels']);
        $this->assertCount(12, $response['penjualan']);
        $this->assertCount(12, $response['pengeluaran']);
    }
}
