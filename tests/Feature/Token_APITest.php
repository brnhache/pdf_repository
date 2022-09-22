<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Token_APITest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test token creation
     *
     * @return void
     */
    public function test_token_creation()
    {
        // TODO: Figure out why this is not working and implement instead of making two requests in a single test.
        // $user = User::factory()->create();

        $this->postJson('/api/auth/createUser', ['name' => 'Sally', 'email' => 'sally@email.com', 'password' => 'thisisapassword']);
        $response = $this->post('/api/auth/getToken', ['email' => "sally@email.com", 'password' => "thisisapassword"]);

        $response->assertStatus(200);
    }
}
