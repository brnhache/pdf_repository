<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APITest extends TestCase
{
    use RefreshDatabase;
    // Create a public token that can be used in the tests
    // public $user = $this->postJson('/api/auth/createUser', ['name' => 'Harry', 'email' => 'harry@email.com', 'password' => 'thisisapassword'])->token;
    /**
     * Test user creation.
     *
     * @return void
     */
    public function test_user_creation()
    {
        // $response = $this->withHeaders([
        //     'auth' => 'Value',
        // ])->post('/user', ['name' => 'Sally']);
        $response = $this->postJson('/api/auth/createUser', ['name' => 'Sally', 'email' => 'sally@email.com', 'password' => 'thisisapassword']);
        $response
            ->assertStatus(200)
            ->assertJson([
                "message" => "User Created Successfully"
            ]);
    }

    public function test_token_creation()
    {
        // TODO: Figure out why this is not working and implement instead of making two requests in a single test.
        // $user = User::factory()->create();

        $this->postJson('/api/auth/createUser', ['name' => 'Sally', 'email' => 'sally@email.com', 'password' => 'thisisapassword']);
        $response = $this->post('/api/auth/getToken', ['email' => "sally@email.com", 'password' => "thisisapassword"]);

        $response->assertStatus(200);
    }

    public function test_document_creation()
    {
        //Todo
    }
}
