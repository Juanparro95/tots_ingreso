<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Space;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpaceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_spaces(): void
    {
        Space::factory()->count(5)->create();

        $response = $this->getJson('/api/spaces');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'type', 'description', 'capacity', 'location', 'hourly_rate']
                ]
            ]);

        $this->assertCount(5, $response['data']);
    }

    public function test_filter_spaces_by_capacity(): void
    {
        Space::factory()->create(['capacity' => 5]);
        Space::factory()->create(['capacity' => 20]);
        Space::factory()->create(['capacity' => 50]);

        $response = $this->getJson('/api/spaces?min_capacity=15&max_capacity=30');

        $response->assertStatus(200);
        $this->assertCount(1, $response['data']);
        $this->assertEquals(20, $response['data'][0]['capacity']);
    }

    public function test_filter_spaces_by_type(): void
    {
        Space::factory()->create(['type' => 'sala']);
        Space::factory()->create(['type' => 'auditorio']);
        Space::factory()->create(['type' => 'conferencia']);

        $response = $this->getJson('/api/spaces?type=auditorio');

        $response->assertStatus(200);
        $this->assertCount(1, $response['data']);
        $this->assertEquals('auditorio', $response['data'][0]['type']);
    }

    public function test_search_spaces_by_name(): void
    {
        Space::factory()->create(['name' => 'Sala Principal']);
        Space::factory()->create(['name' => 'Auditorio Moderno']);

        $response = $this->getJson('/api/spaces?search=Sala');

        $response->assertStatus(200);
        $this->assertCount(1, $response['data']);
        $this->assertStringContainsString('Sala', $response['data'][0]['name']);
    }

    public function test_get_single_space(): void
    {
        $space = Space::factory()->create();

        $response = $this->getJson("/api/spaces/{$space->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $space->id,
                    'name' => $space->name
                ]
            ]);
    }

    public function test_get_nonexistent_space(): void
    {
        $response = $this->getJson('/api/spaces/9999');

        $response->assertStatus(404);
    }

    public function test_create_space_as_admin(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $data = [
            'name' => 'Nueva Sala',
            'type' => 'sala',
            'description' => 'Descripción de prueba',
            'capacity' => 20,
            'location' => 'Piso 1',
            'hourly_rate' => 100.00
        ];

        $response = $this->actingAs($user, 'api')
            ->postJson('/api/spaces', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'name', 'type', 'capacity']]);

        $this->assertDatabaseHas('spaces', ['name' => 'Nueva Sala']);
    }

    public function test_create_space_as_non_admin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $data = [
            'name' => 'Nueva Sala',
            'type' => 'sala',
            'capacity' => 20,
            'location' => 'Piso 1',
            'hourly_rate' => 100.00
        ];

        $response = $this->actingAs($user, 'api')
            ->postJson('/api/spaces', $data);

        $response->assertStatus(403);
    }

    public function test_update_space_as_admin(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $space = Space::factory()->create();

        $data = ['name' => 'Sala Actualizada', 'capacity' => 25];

        $response = $this->actingAs($user, 'api')
            ->putJson("/api/spaces/{$space->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('spaces', ['id' => $space->id, 'name' => 'Sala Actualizada']);
    }

    public function test_delete_space_as_admin(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $space = Space::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->deleteJson("/api/spaces/{$space->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('spaces', ['id' => $space->id]);
    }
}
