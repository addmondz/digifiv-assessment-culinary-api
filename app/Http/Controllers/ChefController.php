<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chef;

/**
 * @OA\Tag(
 *     name="Chefs",
 *     description="API Endpoints for managing chefs"
 * )
 */
class ChefController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/chefs",
     *     summary="Get all chefs",
     *     tags={"Chefs"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of chefs",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="profile", type="string", example="Expert chef specializing in Italian cuisine."),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        $chefs = Chef::all();
        return response()->json(['chefs' => $chefs]);
    }

    /**
     * @OA\Post(
     *     path="/api/chefs",
     *     summary="Create a new chef",
     *     tags={"Chefs"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="profile", type="string", example="Expert chef specializing in Italian cuisine.")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Chef created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="profile", type="string", example="Expert chef specializing in Italian cuisine."),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object"),
     *         ),
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profile' => 'nullable|string',
        ]);

        $chef = Chef::create([
            'name' => $request->name,
            'profile' => $request->profile,
        ]);

        return response()->json(['chef' => $chef], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/chefs/{id}",
     *     summary="Get a specific chef",
     *     tags={"Chefs"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the chef",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chef details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="profile", type="string", example="Expert chef specializing in Italian cuisine."),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chef not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Chef not found"),
     *         ),
     *     ),
     * )
     */
    public function show(int $id)
    {
        $chef = Chef::findOrFail($id);
        return response()->json(['chef' => $chef]);
    }

    /**
     * @OA\Put(
     *     path="/api/chefs/{id}",
     *     summary="Update a chef",
     *     tags={"Chefs"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the chef",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="profile", type="string", example="Expert chef specializing in Italian cuisine.")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chef updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="profile", type="string", example="Expert chef specializing in Italian cuisine."),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chef not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Chef not found"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object"),
     *         ),
     *     ),
     * )
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profile' => 'nullable|string',
        ]);

        $chef = Chef::findOrFail($id);
        $chef->update([
            'name' => $request->name,
            'profile' => $request->profile,
        ]);

        return response()->json(['chef' => $chef]);
    }

    /**
     * @OA\Delete(
     *     path="/api/chefs/{id}",
     *     summary="Delete a chef",
     *     tags={"Chefs"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the chef",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chef deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Chef deleted successfully"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chef not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Chef not found"),
     *         ),
     *     ),
     * )
     */
    public function destroy(int $id)
    {
        $chef = Chef::findOrFail($id);
        $chef->delete();

        return response()->json(['message' => 'Chef deleted successfully']);
    }
}
