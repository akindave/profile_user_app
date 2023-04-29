<?php

namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_user_profile()
    {
        Storage::fake('public');

        $userProfile = User::factory()->create();

        $data = [
            'first_name' => 'Jane',
            'middle_name' => 'Doe',
            'last_name' => 'Smith',
            'phone_number' => '0987654321',
            'email' => 'akin.d@gmial.com',
            'is_admin' => false,
            'profile_image' => UploadedFile::fake()->image('profile.jpg')
        ];

        $response = $this->json('PUT', '/api/users/' . $userProfile->id, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
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
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $userProfile->id,
            'first_name' => 'Jane',
            'middle_name' => 'Doe',
            'last_name' => 'Smith',
            'email' => 'akin.d@gmial.com',
            'is_admin'=>false,
            'phone_number' => '0987654321'
        ]);

        Storage::disk('public')->assertExists($response->json()['data']['profile_image']);
    }

    public function test_delete_user_profile()
    {
        $userProfile = User::factory()->create();

        $response = $this->json('DELETE', '/api/users/' . $userProfile->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $userProfile->id]);

    }
}
