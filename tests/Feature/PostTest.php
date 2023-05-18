<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_users_can_fetch_all_posts(): void
    {
        $response = $this->actingAs($this->user)->get('/api/posts');

        $response->assertOk();
        $response->assertJsonIsArray();
    }

    public function test_new_user_has_no_posts(): void
    {
        $response = $this->actingAs($this->user)->get('/api/posts?filter=user');

        $response->assertOk();
        $response->assertExactJson([]);
    }

    public function test_gives_error_when_invalid_filter_is_applied(): void
    {
        $response = $this->actingAs($this->user)->get('/api/posts?filter=invalid');

        $response->assertUnprocessable();
    }

    public function test_unauthenticated_users_cannot_fetch_posts(): void
    {
        $response = $this->get('/api/posts');

        $response->assertUnauthorized();
    }

    public function test_users_can_create_new_post(): void
    {
        $response = $this->actingAs($this->user)->post('/api/posts', [
            'text_content' => 'Hello',
        ]);

        $response->assertCreated();
    }

    public function test_unauthenticated_users_cannot_create_new_post(): void
    {
        $response = $this->post('/api/posts', [
            'text_content' => 'Hello',
        ]);

        $response->assertUnauthorized();
    }
}
