<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Space;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SpaceController extends Controller
{
    /**
     * Get all spaces with optional filters
     *
     * @OA\Get(
     *     path="/spaces",
     *     tags={"Spaces"},
     *     summary="Get all spaces",
     *     description="Retrieve a list of all available spaces with optional filters",
     *     @OA\Parameter(
     *         name="min_capacity",
     *         in="query",
     *         description="Minimum capacity filter",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="max_capacity",
     *         in="query",
     *         description="Maximum capacity filter",
     *         required=false,
     *         @OA\Schema(type="integer", example=100)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name or description",
     *         required=false,
     *         @OA\Schema(type="string", example="Sala")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by space type (sala, auditorio, conferencia, taller)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"sala", "auditorio", "conferencia", "taller"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Sala A"),
     *                     @OA\Property(property="type", type="string", enum={"sala", "auditorio", "conferencia", "taller"}, example="sala"),
     *                     @OA\Property(property="description", type="string", example="Sala de reuniones pequeña"),
     *                     @OA\Property(property="capacity", type="integer", example=10),
     *                     @OA\Property(property="location", type="string", example="Piso 1"),
     *                     @OA\Property(property="image_url", type="string", nullable=true),
     *                     @OA\Property(property="hourly_rate", type="number", format="float", example=50.00)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Space::query();

        // Filter by capacity
        if ($request->has('min_capacity')) {
            $query->where('capacity', '>=', $request->min_capacity);
        }

        if ($request->has('max_capacity')) {
            $query->where('capacity', '<=', $request->max_capacity);
        }

        // Filter by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->has('type') && $request->type !== 'null' && $request->type !== null) {
            $query->where('type', $request->type);
        }

        $spaces = $query->get();

        return response()->json([
            'data' => $spaces,
        ]);
    }

    /**
     * Get a single space
     *
     * @OA\Get(
     *     path="/spaces/{id}",
     *     tags={"Spaces"},
     *     summary="Get space by ID",
     *     description="Retrieve detailed information about a specific space",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Space ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="capacity", type="integer"),
     *                 @OA\Property(property="reservations", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Space not found")
     * )
     */
    public function show(Space $space): JsonResponse
    {
        return response()->json([
            'data' => $space->load('reservations'),
        ]);
    }

    /**
     * Create a new space (Admin only)
     *
     * @OA\Post(
     *     path="/spaces",
     *     tags={"Spaces"},
     *     summary="Create new space",
     *     description="Create a new space (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","capacity","location","hourly_rate","type"},
     *             @OA\Property(property="name", type="string", example="Sala VIP"),
     *             @OA\Property(property="type", type="string", enum={"sala", "auditorio", "conferencia", "taller"}, example="sala"),
     *             @OA\Property(property="description", type="string", example="Sala ejecutiva con pantalla 4K"),
     *             @OA\Property(property="capacity", type="integer", example=20),
     *             @OA\Property(property="location", type="string", example="Piso 5"),
     *             @OA\Property(property="image_url", type="string", nullable=true),
     *             @OA\Property(property="hourly_rate", type="number", format="float", example=120.00)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Space created successfully"),
     *     @OA\Response(response=403, description="Unauthorized - Admin only"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        // Check if user is admin
        if (!auth('api')->user()->is_admin) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:sala,auditorio,conferencia,taller',
                'description' => 'nullable|string',
                'capacity' => 'required|integer|min:1',
                'location' => 'required|string|max:255',
                'image_url' => 'nullable|string|url',
                'hourly_rate' => 'required|numeric|min:0',
            ]);

            $space = Space::create($validated);

            return response()->json([
                'message' => 'Space created successfully',
                'data' => $space,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Update a space (Admin only)
     *
     * @OA\Put(
     *     path="/spaces/{id}",
     *     tags={"Spaces"},
     *     summary="Update space",
     *     description="Update an existing space (Admin only)",
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
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="type", type="string", enum={"sala", "auditorio", "conferencia", "taller"}),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="capacity", type="integer"),
     *             @OA\Property(property="location", type="string"),
     *             @OA\Property(property="hourly_rate", type="number")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Space updated successfully"),
     *     @OA\Response(response=403, description="Unauthorized - Admin only"),
     *     @OA\Response(response=404, description="Space not found")
     * )
     */
    public function update(Request $request, Space $space): JsonResponse
    {
        if (!auth('api')->user()->is_admin) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'type' => 'sometimes|string|in:sala,auditorio,conferencia,taller',
                'description' => 'sometimes|nullable|string',
                'capacity' => 'sometimes|integer|min:1',
                'location' => 'sometimes|string|max:255',
                'image_url' => 'sometimes|nullable|string|url',
                'hourly_rate' => 'sometimes|numeric|min:0',
            ]);

            $space->update($validated);

            return response()->json([
                'message' => 'Space updated successfully',
                'data' => $space,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Delete a space (Admin only)
     *
     * @OA\Delete(
     *     path="/spaces/{id}",
     *     tags={"Spaces"},
     *     summary="Delete space",
     *     description="Delete an existing space (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Space deleted successfully"),
     *     @OA\Response(response=403, description="Unauthorized - Admin only"),
     *     @OA\Response(response=404, description="Space not found")
     * )
     */
    public function destroy(Space $space): JsonResponse
    {
        if (!auth('api')->user()->is_admin) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $space->delete();

        return response()->json([
            'message' => 'Space deleted successfully',
        ]);
    }
}
