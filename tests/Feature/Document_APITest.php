<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Document_APITest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test document creation.
     *
     * @return void
     */
    public function test_document_creation()
    {
        $this->postJson('/api/auth/createUser', ['name' => 'Sally', 'email' => 'sally@email.com', 'password' => 'thisisapassword']);
        $token = $this->postJson('/api/auth/getToken', ['email' => "sally@email.com", 'password' => "thisisapassword"])->original['token'];
        Storage::fake('pdfs');
        $file = UploadedFile::fake()->image('test.pdf');

        $response = $this->withHeaders(['Authorization', 'Bearer ' . $token])->post('/api/documents', [
            'name' => $file->name,
            'file' => $file,
        ]);

        $response->assertStatus(201);
    }
}
