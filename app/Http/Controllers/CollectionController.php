<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;

class CollectionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/collections",
     *     summary="Get all collections",
     *     tags={"Collections"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of collections",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Collection Name"),
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
        $collections = Collection::all();
        return response()->json($collections);
    }

    /**
     * @OA\Post(
     *     path="/api/collections",
     *     summary="Store a new collection",
     *     tags={"Collections"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="New Collection")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Collection created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="New Collection"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid input."),
     *         ),
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $collection = Collection::create($request->all());
        return response()->json($collection, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/collections/{id}",
     *     summary="Get a collection by ID",
     *     tags={"Collections"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Collection ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Collection details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Collection Name"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Collection not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Collection not found."),
     *         ),
     *     ),
     * )
     */
    public function show(string $id)
    {
        $collection = Collection::findOrFail($id);
        return response()->json($collection);
    }

    /**
     * @OA\Put(
     *     path="/api/collections/{id}",
     *     summary="Update a collection by ID",
     *     tags={"Collections"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Collection ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Collection")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Collection updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Updated Collection"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid input."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Collection not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Collection not found."),
     *         ),
     *     ),
     * )
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $collection = Collection::findOrFail($id);
        $collection->update($request->all());
        return response()->json($collection);
    }

    /**
     * @OA\Delete(
     *     path="/api/collections/{id}",
     *     summary="Delete a collection by ID",
     *     tags={"Collections"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Collection ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Collection deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Collection not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Collection not found."),
     *         ),
     *     ),
     * )
     */
    public function destroy(string $id)
    {
        $collection = Collection::findOrFail($id);
        $collection->delete();
        return response()->json(null, 204);
    }
}
