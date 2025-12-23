<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Space;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_reservation(): void
    {
        $user = User::factory()->create();
        $space = Space::factory()->create();

        $data = [
            'space_id' => $space->id,
            'event_name' => 'Mi Evento',
            'start_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_time' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
            'notes' => 'Notas opcionales'
        ];

        $response = $this->actingAs($user, 'api')
            ->postJson('/api/reservations', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'space_id', 'user_id', 'event_name']]);

        $this->assertDatabaseHas('reservations', ['event_name' => 'Mi Evento']);
    }

    public function test_cannot_create_overlapping_reservation(): void
    {
        $user = User::factory()->create();
        $space = Space::factory()->create();

        $startTime = now()->addDay();
        $endTime = $startTime->copy()->addHours(2);

        // Create first reservation
        Reservation::factory()->create([
            'space_id' => $space->id,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);

        // Try to create overlapping reservation
        $data = [
            'space_id' => $space->id,
            'event_name' => 'Evento Conflictivo',
            'start_time' => $startTime->addMinutes(30)->format('Y-m-d H:i:s'),
            'end_time' => $endTime->addHours(1)->format('Y-m-d H:i:s'),
            'notes' => ''
        ];

        $response = $this->actingAs($user, 'api')
            ->postJson('/api/reservations', $data);

        $response->assertStatus(409);
    }

    public function test_user_can_view_own_reservations(): void
    {
        $user = User::factory()->create();
        $space = Space::factory()->create();

        Reservation::factory()->create([
            'user_id' => $user->id,
            'space_id' => $space->id
        ]);

        Reservation::factory()->create([
            'user_id' => User::factory()->create()->id,
            'space_id' => $space->id
        ]);

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/my-reservations');

        $response->assertStatus(200);
        $this->assertCount(1, $response['data']);
    }

    public function test_user_cannot_view_other_users_reservations(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $space = Space::factory()->create();

        $reservation = Reservation::factory()->create([
            'user_id' => $user2->id,
            'space_id' => $space->id
        ]);

        $response = $this->actingAs($user1, 'api')
            ->getJson("/api/reservations/{$reservation->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_update_own_reservation(): void
    {
        $user = User::factory()->create();
        $space = Space::factory()->create();

        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'space_id' => $space->id,
            'event_name' => 'Evento Original'
        ]);

        $data = [
            'event_name' => 'Evento Actualizado',
            'start_time' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'end_time' => now()->addDays(3)->addHours(3)->format('Y-m-d H:i:s')
        ];

        $response = $this->actingAs($user, 'api')
            ->putJson("/api/reservations/{$reservation->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'event_name' => 'Evento Actualizado'
        ]);
    }

    public function test_user_cannot_update_other_users_reservation(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $space = Space::factory()->create();

        $reservation = Reservation::factory()->create([
            'user_id' => $user2->id,
            'space_id' => $space->id
        ]);

        $data = [
            'event_name' => 'Evento Hackeado',
            'start_time' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'end_time' => now()->addDays(5)->addHours(2)->format('Y-m-d H:i:s')
        ];

        $response = $this->actingAs($user1, 'api')
            ->putJson("/api/reservations/{$reservation->id}", $data);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_reservation(): void
    {
        $user = User::factory()->create();
        $space = Space::factory()->create();

        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'space_id' => $space->id
        ]);

        $response = $this->actingAs($user, 'api')
            ->deleteJson("/api/reservations/{$reservation->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }

    public function test_get_available_slots(): void
    {
        $space = Space::factory()->create();
        $date = now()->addDay()->format('Y-m-d');

        // Create a reservation blocking certain hours
        Reservation::factory()->create([
            'space_id' => $space->id,
            'start_time' => now()->addDay()->setHour(10)->setMinute(0),
            'end_time' => now()->addDay()->setHour(12)->setMinute(0)
        ]);

        $response = $this->getJson("/api/reservations/available-slots?space_id={$space->id}&date={$date}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['*' => ['time', 'available']]]);

        $this->assertGreaterThan(0, count($response['data']));
    }

    public function test_unauthenticated_user_cannot_create_reservation(): void
    {
        $space = Space::factory()->create();

        $data = [
            'space_id' => $space->id,
            'event_name' => 'Evento No Autorizado',
            'start_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_time' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s')
        ];

        $response = $this->postJson('/api/reservations', $data);

        $response->assertStatus(401);
    }
}
