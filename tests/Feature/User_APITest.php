<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class User_APITest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user creation.
     *
     * @return void
     */
    public function test_user_creation()
    {
        $response = $this->postJson('/api/auth/createUser', ['name' => 'Sally', 'email' => 'sally@email.com', 'password' => 'thisisapassword']);
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'User Created Successfully'
            ]);
    }

    /**
     * Test user creation fails properly with an invalid email.
     *
     * @return void
     */
    public function test_user_creation_email_validation_failure()
    {
        $response = $this->postJson('/api/auth/createUser', ['name' => 'Sally', 'email' => 'sally_email.com', 'password' => 'thisisapassword']);
        $response
            ->assertStatus(400)
            ->assertJson([
                'errors' => array('email' => array(0 => 'The email must be a valid email address.',),)
            ]);
    }

    /**
     * Test user creation fails properly without a password.
     *
     * @return void
     */
    public function test_user_creation_password_validation_failure()
    {
        $response = $this->postJson('/api/auth/createUser', ['name' => 'Sally', 'email' => 'sally@email.com']);
        $response
            ->assertStatus(400)
            ->assertJson([
                'errors' => array('password' => array(0 => 'The password field is required.',),)
            ]);
    }

    /**
     * Test user creation fails properly without a password.
     *
     * @return void
     */
    public function test_user_creation_name_validation_failure()
    {
        $response = $this->postJson('/api/auth/createUser', ['email' => 'sally@email.com', 'password' => 'thisisapassword']);
        $response
            ->assertStatus(400)
            ->assertJson([
                'errors' => array('name' => array(0 => 'The name field is required.',),)
            ]);
    }

    /**
     * Test user creation fails properly for duplicate email.
     *
     * @return void
     */
    public function test_user_creation_duplicate_email_failure()
    {
        User::factory()->create([
            'name' => 'freddy',
            'email' => 'dupe@email.com',
            'password' => 'thisisapassword'
        ]);
        $response = $this->postJson('/api/auth/createUser', ['name' => 'sally', 'email' => 'dupe@email.com', 'password' => 'thisisapassword']);
        $response
            ->assertStatus(409)
            ->assertJson([
                'error' => 'This email already exists in the system.'
            ]);
    }
}
