<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    private string $token;
    private User $user;
    private Space $space;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
        $this->space = Space::factory()->create();
    }

    public function test_user_can_create_reservation(): void
    {
        $response = $this->postJson('/api/reservations', [
            'space_id' => $this->space->id,
            'event_name' => 'Team Meeting',
            'start_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_time' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
            'notes' => 'Important meeting',
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'data' => ['id', 'space_id', 'user_id', 'event_name'],
                 ]);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $this->user->id,
            'space_id' => $this->space->id,
            'event_name' => 'Team Meeting',
        ]);
    }

    public function test_user_can_view_own_reservations(): void
    {
        $reservation = Reservation::factory()->create([
            'user_id' => $this->user->id,
            'space_id' => $this->space->id,
        ]);

        $response = $this->getJson('/api/reservations', [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'event_name', 'start_time'],
                     ],
                 ]);
    }

    public function test_cannot_create_overlapping_reservations(): void
    {
        $startTime = now()->addDay()->format('Y-m-d H:i:s');
        $endTime = now()->addDay()->addHours(2)->format('Y-m-d H:i:s');

        // Create first reservation
        Reservation::create([
            'space_id' => $this->space->id,
            'user_id' => $this->user->id,
            'event_name' => 'First Event',
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        // Try to create overlapping reservation
        $response = $this->postJson('/api/reservations', [
            'space_id' => $this->space->id,
            'event_name' => 'Second Event',
            'start_time' => $startTime,
            'end_time' => $endTime,
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(409);
    }

    public function test_user_can_update_own_reservation(): void
    {
        $reservation = Reservation::factory()->create([
            'user_id' => $this->user->id,
            'space_id' => $this->space->id,
        ]);

        $newStartTime = now()->addDays(3)->format('Y-m-d H:i:s');
        $newEndTime = now()->addDays(3)->addHours(2)->format('Y-m-d H:i:s');

        $response = $this->putJson("/api/reservations/{$reservation->id}", [
            'event_name' => 'Updated Event',
            'start_time' => $newStartTime,
            'end_time' => $newEndTime,
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'event_name' => 'Updated Event',
        ]);
    }

    public function test_user_can_delete_own_reservation(): void
    {
        $reservation = Reservation::factory()->create([
            'user_id' => $this->user->id,
            'space_id' => $this->space->id,
        ]);

        $response = $this->deleteJson("/api/reservations/{$reservation->id}", [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }

    public function test_user_cannot_delete_others_reservation(): void
    {
        $otherUser = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $otherUser->id,
            'space_id' => $this->space->id,
        ]);

        $response = $this->deleteJson("/api/reservations/{$reservation->id}", [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(403);
    }
}
