<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testRegistration()
    {
        $payload = [
            'first_name' => 'Akintan',
            'middle_name' => 'David',
            'last_name' => 'olu',
            'phone_number' => '1234567890',
            'email' => 'akin.dave@example.com',
            'password' => 'password123',
            'profile_image' => UploadedFile::fake()->image('profile.jpg'),
            'is_admin' => false
        ];

        $response = $this->json('POST', '/api/users/register', $payload);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'token' ,
                'user' => [
                    'id',
                    'first_name',
                    'middle_name',
                    'last_name',
                    'phone_number',
                    'profile_image',
                    'email',
                    'is_admin',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'first_name' => 'Akintan',
            'middle_name' => 'David',
            'last_name' => 'olu',
            'phone_number' => '1234567890',
            'email' => 'akin.dave@example.com'
        ]);
    }

    public function testLogin()
    {
        $user = User::factory()->create([
            'phone_number' => '1234567890',
            'password' => bcrypt('password123'),
        ]);

        $payload = [
            'phone_number' => '1234567890',
            'password' => 'password123',
        ];

        $response = $this->json('POST', '/api/users/login', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token' ,
                    'user' => [
                        'id',
                        'first_name',
                        'middle_name',
                        'last_name',
                        'phone_number',
                        'profile_image',
                        'email',
                        'is_admin',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }


}
