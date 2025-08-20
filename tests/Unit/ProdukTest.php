<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class ProdukTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $kategori;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->user = User::where('username', 'admin')->first();
        $this->actingAs($this->user);

        $this->kategori = KategoriProduk::create(['nama' => 'Elektronik']);
    }

    // ---------- POSITIF TEST ---------- //

    #[Test]
    public function user_bisa_melihat_daftar_produk()
    {
        Produk::create([
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => 10000000,
            'stok' => 10,
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response = $this->get(route('produk.index'));

        $response->assertStatus(200);
        $response->assertSee('Laptop');
    }

    #[Test]
    public function user_bisa_menambah_produk_valid()
    {
        Storage::fake('public');

        $response = $this->post(route('produk.store'), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => 15000000,
            'stok' => 5,
            'gambar' => UploadedFile::fake()->image('laptop.jpg')->size(500),
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertRedirect(route('produk.index'));
        $this->assertDatabaseHas('produks', ['nama' => 'Laptop']);

        $produk = Produk::first();
        Storage::disk('public')->assertExists($produk->gambar);
    }

    #[Test]
    public function user_bisa_update_produk()
    {
        $produk = Produk::create([
            'tanggal' => now()->toDateString(),
            'nama' => 'HP',
            'harga' => 2000000,
            'stok' => 20,
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response = $this->put(route('produk.update', $produk->id), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Smartphone',
            'harga' => 2500000,
            'stok' => 25,
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertRedirect(route('produk.index'));
        $this->assertDatabaseHas('produks', ['id' => $produk->id, 'nama' => 'Smartphone']);
    }

    #[Test]
    public function user_bisa_mencari_produk_di_index()
    {
        $produk1 = Produk::create([
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => 15000000,
            'stok' => 5,
            'kategori_produk_id' => $this->kategori->id,
            'gambar' => null,
        ]);

        $produk2 = Produk::create([
            'tanggal' => now()->toDateString(),
            'nama' => 'Mouse',
            'harga' => 500000,
            'stok' => 10,
            'kategori_produk_id' => $this->kategori->id,
            'gambar' => null,
        ]);

        $response = $this->get(route('produk.index', ['search' => 'Laptop']));

        $response->assertSeeText('Laptop');
        $response->assertDontSeeText('Mouse');
    }

    #[Test]
    public function user_bisa_menghapus_produk()
    {
        $produk = Produk::create([
            'tanggal' => now()->toDateString(),
            'nama' => 'Mouse',
            'harga' => 100000,
            'stok' => 50,
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response = $this->delete(route('produk.destroy', $produk->id));

        $response->assertRedirect(route('produk.index'));
        $this->assertDatabaseMissing('produks', ['id' => $produk->id]);
    }

    // ---------- NEGATIF / VALIDASI GAGAL ---------- //

    #[Test]
    public function gagal_menambah_produk_tanpa_tanggal()
    {
        $response = $this->post(route('produk.store'), [
            'tanggal' => '',
            'nama' => 'Laptop',
            'harga' => 15000000,
            'stok' => 5,
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertSessionHasErrors(['tanggal' => 'Tanggal wajib diisi.']);
    }

    #[Test]
    public function gagal_menambah_produk_dengan_tanggal_di_masa_depan()
    {
        $future = now()->addDay()->toDateString();

        $response = $this->post(route('produk.store'), [
            'tanggal' => $future,
            'nama' => 'Laptop',
            'harga' => 15000000,
            'stok' => 5,
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertSessionHasErrors(['tanggal' => 'Tanggal tidak boleh di masa depan.']);
    }

    #[Test]
    public function gagal_menambah_produk_dengan_nama_invalid()
    {
        $longName = str_repeat('A', 46);

        $cases = [
            ['nama' => 'AB', 'error' => 'Nama produk minimal 3 karakter.'],
            ['nama' => $longName, 'error' => 'Nama produk maksimal 45 karakter.'],
            ['nama' => 'Laptop@123', 'error' => 'Nama produk hanya boleh berisi huruf, angka, dan spasi.'],
        ];

        foreach ($cases as $case) {
            $response = $this->post(route('produk.store'), [
                'tanggal' => now()->toDateString(),
                'nama' => $case['nama'],
                'harga' => 15000000,
                'stok' => 5,
                'kategori_produk_id' => $this->kategori->id,
            ]);

            $response->assertSessionHasErrors(['nama' => $case['error']]);
        }
    }

    #[Test]
    public function gagal_menambah_produk_tanpa_harga()
    {
        $response = $this->post(route('produk.store'), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => '',
            'stok' => 5,
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertSessionHasErrors(['harga' => 'Harga wajib diisi.']);
    }

    #[Test]
    public function gagal_menambah_produk_dengan_harga_bukan_angka()
    {
        $response = $this->post(route('produk.store'), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => 'abc',
            'stok' => 5,
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertSessionHasErrors(['harga' => 'Harga harus berupa angka.']);
    }

    #[Test]
    public function gagal_menambah_produk_dengan_harga_negatif()
    {
        $response = $this->post(route('produk.store'), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => -100,
            'stok' => 5,
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertSessionHasErrors(['harga' => 'Harga minimal 0.']);
    }

    #[Test]
    public function gagal_menambah_produk_dengan_harga_terlalu_besar()
    {
        $response = $this->post(route('produk.store'), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => 100000000,
            'stok' => 5,
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertSessionHasErrors(['harga' => 'Harga maksimal 99.999.999,99.']);
    }

    #[Test]
    public function gagal_menambah_produk_tanpa_stok()
    {
        $response = $this->post(route('produk.store'), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => 15000000,
            'stok' => '',
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertSessionHasErrors(['stok' => 'Stok wajib diisi.']);
    }

    #[Test]
    public function gagal_menambah_produk_dengan_stok_bukan_angka()
    {
        $response = $this->post(route('produk.store'), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => 15000000,
            'stok' => 'abc',
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertSessionHasErrors(['stok' => 'Stok harus berupa angka bulat.']);
    }

    #[Test]
    public function gagal_menambah_produk_dengan_stok_negatif()
    {
        $response = $this->post(route('produk.store'), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => 15000000,
            'stok' => -5,
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertSessionHasErrors(['stok' => 'Stok minimal 0.']);
    }

    #[Test]
    public function gagal_menambah_produk_dengan_stok_terlalu_besar()
    {
        $response = $this->post(route('produk.store'), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => 15000000,
            'stok' => 100001,
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertSessionHasErrors(['stok' => 'Stok maksimal 100.000.']);
    }

    #[Test]
    public function gagal_menambah_produk_dengan_gambar_bukan_gambar()
    {
        Storage::fake('public');

        $response = $this->post(route('produk.store'), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => 15000000,
            'stok' => 5,
            'gambar' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertSessionHasErrors(['gambar' => 'File harus berupa gambar.']);
    }

    #[Test]
    public function gagal_menambah_produk_dengan_gambar_terlalu_besar()
    {
        Storage::fake('public');

        $response = $this->post(route('produk.store'), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => 15000000,
            'stok' => 5,
            'gambar' => UploadedFile::fake()->image('laptop.jpg')->size(3072),
            'kategori_produk_id' => $this->kategori->id,
        ]);

        $response->assertSessionHasErrors(['gambar' => 'Ukuran gambar maksimal 2MB.']);
    }

    #[Test]
    public function gagal_menambah_produk_dengan_kategori_tidak_valid()
    {
        $response = $this->post(route('produk.store'), [
            'tanggal' => now()->toDateString(),
            'nama' => 'Laptop',
            'harga' => 15000000,
            'stok' => 5,
            'kategori_produk_id' => 9999,
        ]);

        $response->assertSessionHasErrors(['kategori_produk_id' => 'Kategori produk tidak valid.']);
    }
}
