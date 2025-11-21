<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase; // ده مهم جدًا
    public function authenticate()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $login = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $token = $login['token'];

        return ['user' => $user, 'token' => $token];
    }
    public function test_can_get_posts_list()
    {
        $auth = $this->authenticate();

        $response = $this->getJson('/api/posts', [
            'Authorization' => "Bearer {$auth['token']}"
        ]);

        $response->assertStatus(200);
    }
    public function test_can_create_post()
    {
        $auth = $this->authenticate();

        $response = $this->postJson('/api/posts', [
            'title' => 'New Post',
            'content' => 'Some content',
            'category' => 'Technology'
        ], [
            'Authorization' => "Bearer {$auth['token']}"
        ]);

        $response->assertStatus(201)
            ->assertJson(['title' => 'New Post']);

        $this->assertDatabaseHas('posts', ['title' => 'New Post']);
    }
    public function test_can_show_post()
    {
        $auth = $this->authenticate();
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}", [
            'Authorization' => "Bearer {$auth['token']}"
        ]);

        $response->assertStatus(200)
            ->assertJson(['id' => $post->id]);
    }
    public function test_author_can_update_his_post()
    {
        $auth = $this->authenticate();

        $post = Post::factory()->create([
            'author_id' => $auth['user']->id
        ]);

        $response = $this->putJson("/api/posts/{$post->id}", [
            'title' => 'Updated Title'
        ], [
            'Authorization' => "Bearer {$auth['token']}"
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
    }
    public function test_can_search_posts()
    {
        $auth = $this->authenticate();

        Post::factory()->create(['title' => 'Laravel Tips']);

        $response = $this->getJson('/api/search_filter?search=Laravel', [
            'Authorization' => "Bearer {$auth['token']}"
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Laravel Tips']);
    }
}