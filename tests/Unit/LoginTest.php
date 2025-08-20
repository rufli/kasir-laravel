<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Jalankan seeder supaya user admin tersedia
        $this->seed();
    }

    #[Test]
    public function login_berhasil_dengan_data_valid()
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/dashboard'); // sesuaikan dengan route setelah login
        $this->assertAuthenticated();
    }

    #[Test]
    public function login_gagal_dengan_password_salah()
    {
        $response = $this->from('/login')->post('/login', [
            'username' => 'admin',
            'password' => 'salah',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    #[Test]
    public function login_gagal_dengan_username_tidak_terdaftar()
    {
        $response = $this->from('/login')->post('/login', [
            'username' => 'bukanadmin',
            'password' => '123456',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    #[Test]
    public function login_gagal_dengan_username_kosong()
    {
        $response = $this->from('/login')->post('/login', [
            'username' => '',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest();
    }

    #[Test]
    public function login_gagal_dengan_password_kosong()
    {
        $response = $this->from('/login')->post('/login', [
            'username' => 'admin',
            'password' => '',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }



    #[Test]
    public function login_gagal_dengan_username_too_long()
    {
        $username = str_repeat('a', 21);
        $response = $this->from('/login')->post('/login', [
            'username' => $username,
            'password' => '12345678',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest();
    }

    #[Test]
    public function login_gagal_dengan_username_too_short()
    {
        $response = $this->from('/login')->post('/login', [
            'username' => 'ad', // terlalu pendek
            'password' => '12345678',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest();
    }

    #[Test]
    public function login_gagal_dengan_password_too_short()
    {
        $response = $this->from('/login')->post('/login', [
            'username' => 'admin',
            'password' => '123', // kurang dari 8
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    #[Test]
    public function login_gagal_dengan_password_too_long()
    {
        $password = str_repeat('a', 73); // lebih dari 72
        $response = $this->from('/login')->post('/login', [
            'username' => 'admin',
            'password' => $password,
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }
}
