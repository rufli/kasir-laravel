<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\KategoriPengeluaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class KategoriPengeluaranTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::where('username', 'admin')->first();

        $this->actingAs($this->user);
    }

    #[Test]
    public function user_bisa_melihat_daftar_kategori_pengeluaran()
    {
        KategoriPengeluaran::create(['nama' => 'Listrik']);
        KategoriPengeluaran::create(['nama' => 'Air']);

        $response = $this->get(route('kategori_pengeluaran.index'));

        $response->assertStatus(200);
        $response->assertSee('Listrik');
        $response->assertSee('Air');
    }

    #[Test]
    public function user_bisa_menambah_kategori_pengeluaran()
    {
        $response = $this->post(route('kategori_pengeluaran.store'), [
            'nama' => 'Transportasi'
        ]);

        $response->assertRedirect(route('kategori_pengeluaran.index'));
        $this->assertDatabaseHas('kategori_pengeluaran', [
            'nama' => 'Transportasi'
        ]);
    }

    #[Test]
    public function user_bisa_update_kategori_pengeluaran()
    {
        $kategori = KategoriPengeluaran::create(['nama' => 'Makanan']);

        $response = $this->put(route('kategori_pengeluaran.update', $kategori->id), [
            'nama' => 'Minuman'
        ]);

        $response->assertRedirect(route('kategori_pengeluaran.index'));
        $this->assertDatabaseHas('kategori_pengeluaran', [
            'id' => $kategori->id,
            'nama' => 'Minuman'
        ]);
    }

    #[Test]
    public function user_bisa_menghapus_kategori_pengeluaran()
    {
        $kategori = KategoriPengeluaran::create(['nama' => 'Internet']);

        $response = $this->delete(route('kategori_pengeluaran.destroy', $kategori->id));

        $response->assertRedirect(route('kategori_pengeluaran.index'));
        $this->assertDatabaseMissing('kategori_pengeluaran', [
            'id' => $kategori->id,
        ]);
    }

    #[Test]
    public function gagal_menambah_kategori_pengeluaran_dengan_nama_kosong()
    {
        $response = $this->from(route('kategori_pengeluaran.create'))
            ->post(route('kategori_pengeluaran.store'), ['nama' => '']);

        $response->assertRedirect(route('kategori_pengeluaran.create'));
        $response->assertSessionHasErrors(['nama']);
        $this->assertDatabaseMissing('kategori_pengeluaran', ['nama' => '']);
    }

    #[Test]
    public function gagal_menambah_kategori_pengeluaran_dengan_nama_too_short()
    {
        $response = $this->post(route('kategori_pengeluaran.store'), [
            'nama' => 'Ab'
        ]);

        $response->assertSessionHasErrors(['nama']);
        $this->assertDatabaseMissing('kategori_pengeluaran', ['nama' => 'Ab']);
    }

    #[Test]
    public function gagal_menambah_kategori_pengeluaran_dengan_nama_too_long()
    {
        $nama = str_repeat('a', 46);

        $response = $this->post(route('kategori_pengeluaran.store'), [
            'nama' => $nama
        ]);

        $response->assertSessionHasErrors(['nama']);
        $this->assertDatabaseMissing('kategori_pengeluaran', ['nama' => $nama]);
    }

    #[Test]
    public function gagal_menambah_kategori_pengeluaran_dengan_nama_mengandung_angka()
    {
        $response = $this->post(route('kategori_pengeluaran.store'), [
            'nama' => 'Tagihan123'
        ]);

        $response->assertSessionHasErrors(['nama']);
        $this->assertDatabaseMissing('kategori_pengeluaran', ['nama' => 'Tagihan123']);
    }

    #[Test]
    public function gagal_menambah_kategori_pengeluaran_dengan_nama_duplicate()
    {
        KategoriPengeluaran::create(['nama' => 'Listrik']);

        $response = $this->post(route('kategori_pengeluaran.store'), [
            'nama' => 'Listrik'
        ]);

        $response->assertSessionHasErrors(['nama']);
        // Pastikan data lama tetap ada, tapi tidak ada duplikat baru
        $this->assertDatabaseHas('kategori_pengeluaran', ['nama' => 'Listrik']);
    }
}
