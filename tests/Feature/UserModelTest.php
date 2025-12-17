<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_dapat_membuat_user_baru()
    {
        User::create([
            'nama' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->assertDatabaseHas('user', [
            'email' => 'jane.doe@example.com',
        ]);
    }

    public function test_dapat_mengambil_user_berdasarkan_id()
    {
        $user = User::create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        $foundUser = User::find($user->id_user);

        $this->assertNotNull($foundUser);
        $this->assertEquals('John Doe', $foundUser->nama);
    }
}
