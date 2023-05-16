<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_users_can_authenticate_using_the_login_endpoint(): void
    {
        $response = $this->post('/api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['access_token', 'token_type']);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonStructure(['errors']);
    }
}
