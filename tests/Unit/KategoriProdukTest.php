<?php

namespace Tests\Unit; 

use Tests\TestCase;
use App\Models\User;
use App\Models\KategoriProduk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class KategoriProdukTest extends TestCase
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
    public function user_bisa_melihat_daftar_kategori()
    {
        KategoriProduk::create(['nama' => 'Cemilan']);
        KategoriProduk::create(['nama' => 'Snack']);

        $response = $this->get(route('kategori_produk.index'));

        $response->assertStatus(200);
        $response->assertSee('Makanan');
        $response->assertSee('Minuman');
    }

    #[Test]
    public function user_bisa_menambah_kategori()
    {
        $response = $this->post(route('kategori_produk.store'), [
            'nama' => 'Elektronik'
        ]);

        $response->assertRedirect(route('kategori_produk.index'));
        $this->assertDatabaseHas('kategori_produks', [
            'nama' => 'Elektronik'
        ]);
    }

    #[Test]
    public function user_bisa_update_kategori()
    {
        $kategori = KategoriProduk::create(['nama' => 'Fashion']);

        $response = $this->put(route('kategori_produk.update', $kategori->id), [
            'nama' => 'Pakaian'
        ]);

        $response->assertRedirect(route('kategori_produk.index'));
        $this->assertDatabaseHas('kategori_produks', [
            'id' => $kategori->id,
            'nama' => 'Pakaian'
        ]);
    }

    #[Test]
    public function user_bisa_menghapus_kategori()
    {
        $kategori = KategoriProduk::create(['nama' => 'Aksesoris']);

        $response = $this->delete(route('kategori_produk.destroy', $kategori->id));

        $response->assertRedirect(route('kategori_produk.index'));
        $this->assertDatabaseMissing('kategori_produks', [
            'id' => $kategori->id,
        ]);
    }

    #[Test]
    public function gagal_menambah_kategori_dengan_nama_kosong()
    {
        $response = $this->from(route('kategori_produk.create'))
            ->post(route('kategori_produk.store'), ['nama' => '']);

        $response->assertRedirect(route('kategori_produk.create'));
        $response->assertSessionHasErrors(['nama']);
        $this->assertDatabaseMissing('kategori_produks', ['nama' => '']);
    }

    #[Test]
    public function gagal_menambah_kategori_dengan_nama_too_short()
    {
        $response = $this->post(route('kategori_produk.store'), [
            'nama' => 'Ab'
        ]);

        $response->assertSessionHasErrors(['nama']);
        $this->assertDatabaseMissing('kategori_produks', ['nama' => 'Ab']);
    }

    #[Test]
    public function gagal_menambah_kategori_dengan_nama_too_long()
    {
        $nama = str_repeat('a', 46);

        $response = $this->post(route('kategori_produk.store'), [
            'nama' => $nama
        ]);

        $response->assertSessionHasErrors(['nama']);
        $this->assertDatabaseMissing('kategori_produks', ['nama' => $nama]);
    }

    #[Test]
    public function gagal_menambah_kategori_dengan_nama_mengandung_angka()
    {
        $response = $this->post(route('kategori_produk.store'), [
            'nama' => 'Kategori123'
        ]);

        $response->assertSessionHasErrors(['nama']);
        $this->assertDatabaseMissing('kategori_produks', ['nama' => 'Kategori123']);
    }

    #[Test]
    public function gagal_menambah_kategori_dengan_nama_duplicate()
    {
        KategoriProduk::create(['nama' => 'Elektronik']);

        $response = $this->post(route('kategori_produk.store'), [
            'nama' => 'Elektronik'
        ]);

        $response->assertSessionHasErrors(['nama']);
        // Pastikan data lama tetap ada, tapi tidak ada duplikat baru
        $this->assertDatabaseHas('kategori_produks', ['nama' => 'Elektronik']);
    }
}
