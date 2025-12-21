<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Space;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    /**
     * Get all reservations for the current user
     *
     * @OA\Get(
     *     path="/reservations",
     *     tags={"Reservations"},
     *     summary="Get user's reservations",
     *     description="Retrieve all reservations for the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="space_id", type="integer"),
     *                     @OA\Property(property="user_id", type="integer"),
     *                     @OA\Property(property="event_name", type="string"),
     *                     @OA\Property(property="start_time", type="string", format="date-time"),
     *                     @OA\Property(property="end_time", type="string", format="date-time"),
     *                     @OA\Property(property="notes", type="string", nullable=true),
     *                     @OA\Property(property="space", type="object")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): JsonResponse
    {
        $user = auth('api')->user();
        $reservations = $user->reservations()->with('space')->get();

        return response()->json([
            'data' => $reservations,
        ]);
    }

    /**
     * Get a single reservation (if it belongs to current user)
     *
     * @OA\Get(
     *     path="/reservations/{id}",
     *     tags={"Reservations"},
     *     summary="Get reservation by ID",
     *     description="Retrieve a specific reservation if it belongs to the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Reservation not found")
     * )
     */
    public function show(Reservation $reservation): JsonResponse
    {
        $user = auth('api')->user();

        if ($reservation->user_id !== $user->id && !$user->is_admin) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'data' => $reservation->load('space', 'user'),
        ]);
    }

    /**
     * Create a new reservation
     *
     * @OA\Post(
     *     path="/reservations",
     *     tags={"Reservations"},
     *     summary="Create new reservation",
     *     description="Create a new reservation for a space",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"space_id","event_name","start_time","end_time"},
     *             @OA\Property(property="space_id", type="integer", example=1),
     *             @OA\Property(property="event_name", type="string", example="Reunión de equipo"),
     *             @OA\Property(property="start_time", type="string", format="date-time", example="2025-12-25 10:00:00"),
     *             @OA\Property(property="end_time", type="string", format="date-time", example="2025-12-25 12:00:00"),
     *             @OA\Property(property="notes", type="string", nullable=true, example="Necesitamos proyector")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Reservation created successfully"),
     *     @OA\Response(response=409, description="Space not available"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'space_id' => 'required|exists:spaces,id',
                'event_name' => 'required|string|max:255',
                'start_time' => 'required|date_format:Y-m-d H:i:s',
                'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
                'notes' => 'nullable|string',
            ]);

            $user = auth('api')->user();
            $space = Space::find($validated['space_id']);

            // Check if the space is available during the requested time
            if (!$this->isSpaceAvailable($space, $validated['start_time'], $validated['end_time'])) {
                return response()->json([
                    'message' => 'Space is not available during the requested time',
                ], 409);
            }

            $reservation = Reservation::create([
                'space_id' => $validated['space_id'],
                'user_id' => $user->id,
                'event_name' => $validated['event_name'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'notes' => $validated['notes'] ?? null,
            ]);

            return response()->json([
                'message' => 'Reservation created successfully',
                'data' => $reservation->load('space'),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Update a reservation
     *
     * @OA\Put(
     *     path="/reservations/{id}",
     *     tags={"Reservations"},
     *     summary="Update reservation",
     *     description="Update an existing reservation",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="event_name", type="string"),
     *             @OA\Property(property="start_time", type="string", format="date-time"),
     *             @OA\Property(property="end_time", type="string", format="date-time"),
     *             @OA\Property(property="notes", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Reservation updated successfully"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=409, description="Space not available")
     * )
     */
    public function update(Request $request, Reservation $reservation): JsonResponse
    {
        $user = auth('api')->user();

        if ($reservation->user_id !== $user->id && !$user->is_admin) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'event_name' => 'sometimes|string|max:255',
                'start_time' => 'sometimes|date_format:Y-m-d H:i:s',
                'end_time' => 'sometimes|date_format:Y-m-d H:i:s',
                'notes' => 'sometimes|nullable|string',
            ]);

            // If dates changed, verify availability
            if (isset($validated['start_time']) || isset($validated['end_time'])) {
                $startTime = $validated['start_time'] ?? $reservation->start_time;
                $endTime = $validated['end_time'] ?? $reservation->end_time;

                if (!$this->isSpaceAvailable($reservation->space, $startTime, $endTime, $reservation->id)) {
                    return response()->json([
                        'message' => 'Space is not available during the requested time',
                    ], 409);
                }
            }

            $reservation->update($validated);

            return response()->json([
                'message' => 'Reservation updated successfully',
                'data' => $reservation->load('space'),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Delete a reservation
     *
     * @OA\Delete(
     *     path="/reservations/{id}",
     *     tags={"Reservations"},
     *     summary="Delete reservation",
     *     description="Cancel/delete an existing reservation",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Reservation deleted successfully"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Reservation not found")
     * )
     */
    public function destroy(Reservation $reservation): JsonResponse
    {
        $user = auth('api')->user();

        if ($reservation->user_id !== $user->id && !$user->is_admin) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $reservation->delete();

        return response()->json([
            'message' => 'Reservation deleted successfully',
        ]);
    }

    /**
     * Get available slots for a space on a specific date
     *
     * @OA\Get(
     *     path="/reservations/available-slots",
     *     tags={"Reservations"},
     *     summary="Get available time slots",
     *     description="Get available hourly slots for a space on a specific date (8 AM - 6 PM)",
     *     @OA\Parameter(
     *         name="space_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", format="date", example="2025-12-25")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="start", type="string", example="08:00"),
     *                     @OA\Property(property="end", type="string", example="09:00"),
     *                     @OA\Property(property="available", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function getAvailableSlots(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'space_id' => 'required|exists:spaces,id',
                'date' => 'required|date_format:Y-m-d',
            ]);

            $spaceId = $request->space_id;
            $date = $request->date;

            // Get all reservations for this space on the given date
            $reservations = Reservation::where('space_id', $spaceId)
                ->whereDate('start_time', $date)
                ->orderBy('start_time')
                ->get(['start_time', 'end_time']);

            // Generate available slots (every hour from 8am to 6pm)
            $slots = $this->generateAvailableSlots($date, $reservations);

            return response()->json([
                'data' => $slots,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Check if a space is available during the requested time
     * @param Space $space
     * @param string $startTime
     * @param string $endTime
     * @param int|null $excludeReservationId
     * @return bool
     */
    private function isSpaceAvailable(Space $space, string $startTime, string $endTime, int|null $excludeReservationId = null): bool
    {
        $query = Reservation::where('space_id', $space->id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($query) use ($startTime, $endTime) {
                          $query->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                      });
            });

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return !$query->exists();
    }

    /**
     * Generate available time slots for a given date
     * @param string $date
     * @param $reservations
     * @return array
     */
    private function generateAvailableSlots(string $date, $reservations): array
    {
        $slots = [];
        $start = strtotime($date . ' 08:00:00');
        $end = strtotime($date . ' 18:00:00');

        for ($time = $start; $time < $end; $time += 3600) {
            $slotStart = date('Y-m-d H:i:s', $time);
            $slotEnd = date('Y-m-d H:i:s', $time + 3600);

            $isAvailable = true;
            foreach ($reservations as $reservation) {
                if (
                    ($reservation->start_time <= $slotStart && $reservation->end_time > $slotStart) ||
                    ($reservation->start_time < $slotEnd && $reservation->end_time >= $slotEnd) ||
                    ($reservation->start_time >= $slotStart && $reservation->end_time <= $slotEnd)
                ) {
                    $isAvailable = false;
                    break;
                }
            }

            $slots[] = [
                'start' => $slotStart,
                'end' => $slotEnd,
                'available' => $isAvailable,
            ];
        }

        return $slots;
    }
}
