<?php

namespace Tests\Feature;

use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class SpaceTest extends TestCase
{
    use RefreshDatabase;

    private string $token;
    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create regular user
        $this->user = User::factory()->create(['is_admin' => false]);
        $this->token = JWTAuth::fromUser($this->user);

        // Create admin user
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_can_list_spaces(): void
    {
        Space::factory()->count(5)->create();

        $response = $this->getJson('/api/spaces', [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'capacity', 'created_at'],
                     ],
                 ])
                 ->assertJsonCount(5, 'data');
    }

    public function test_can_show_single_space(): void
    {
        $space = Space::factory()->create();

        $response = $this->getJson("/api/spaces/{$space->id}", [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => ['id', 'name', 'capacity', 'created_at'],
                 ]);
    }

    public function test_admin_can_create_space(): void
    {
        $adminToken = JWTAuth::fromUser($this->admin);

        $response = $this->postJson('/api/spaces', [
            'name' => 'Test Space',
            'type' => 'sala',
            'description' => 'A test space',
            'capacity' => 20,
            'location' => 'Building A',
            'hourly_rate' => 100,
        ], [
            'Authorization' => "Bearer {$adminToken}",
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'data' => ['id', 'name', 'type', 'capacity'],
                 ]);

        $this->assertDatabaseHas('spaces', [
            'name' => 'Test Space',
            'type' => 'sala',
        ]);
    }

    public function test_non_admin_cannot_create_space(): void
    {
        $response = $this->postJson('/api/spaces', [
            'name' => 'Test Space',
            'type' => 'sala',
            'capacity' => 20,
            'location' => 'Building A',
            'hourly_rate' => 100,
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_space(): void
    {
        $space = Space::factory()->create();
        $adminToken = JWTAuth::fromUser($this->admin);

        $response = $this->putJson("/api/spaces/{$space->id}", [
            'name' => 'Updated Space',
            'capacity' => 30,
        ], [
            'Authorization' => "Bearer {$adminToken}",
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('spaces', [
            'id' => $space->id,
            'name' => 'Updated Space',
        ]);
    }

    public function test_admin_can_delete_space(): void
    {
        $space = Space::factory()->create();
        $adminToken = JWTAuth::fromUser($this->admin);

        $response = $this->deleteJson("/api/spaces/{$space->id}", [], [
            'Authorization' => "Bearer {$adminToken}",
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('spaces', ['id' => $space->id]);
    }
}
