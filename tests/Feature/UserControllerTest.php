<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dapat_mengambil_semua_users(): void
    {
        // Arrange
        User::create([
            'nama' => 'User Satu',
            'email' => 'user1@example.com',
            'password' => bcrypt('password123'),
        ]);

        User::create([
            'nama' => 'User Dua',
            'email' => 'user2@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Act
        $response = $this->getJson('/api/users');

        // Assert
        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }

    public function test_return_404_jika_user_tidak_ditemukan(): void
    {
        $response = $this->getJson('/api/users/999');

        $response->assertStatus(404);
    }

    public function test_dapat_membuat_user_baru(): void
    {
        // Act
        $response = $this->postJson('/api/users', [
            'nama' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
        ]);

        // Assert
        $response->assertStatus(201);

        $this->assertDatabaseHas('user', [
            'email' => 'newuser@example.com',
        ]);
    }
}
