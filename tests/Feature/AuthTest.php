<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase; // ده مهم جدًا
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Mohamed',
            'email' => 'm@m.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role' => 'author',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'm@m.com']);
    }
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }
}
