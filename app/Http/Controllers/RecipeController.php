<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/recipes",
     *     summary="Get all recipes",
     *     tags={"Recipes"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of recipes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Spaghetti Carbonara"),
     *                 @OA\Property(property="unique_code", type="string", example="REC123"),
     *                 @OA\Property(property="chef_id", type="integer", example=1),
     *                 @OA\Property(property="ingredients", type="array", @OA\Items(type="string"), example={"flour", "sugar"}),
     *                 @OA\Property(property="likes_count", type="integer", example=10),
     *                 @OA\Property(property="dislikes_count", type="integer", example=2),
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
        $recipes = Recipe::with(['chef','collections','tags'])->get();
        return response()->json($recipes);
    }

    /**
     * @OA\Post(
     *     path="/api/recipes/create",
     *     summary="Store a new recipe",
     *     tags={"Recipes"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "unique_code", "chef_id"},
     *             @OA\Property(property="name", type="string", example="New Recipe"),
     *             @OA\Property(property="unique_code", type="string", example="REC123"),
     *             @OA\Property(property="chef_id", type="integer", example=1),
     *             @OA\Property(property="ingredients", type="array", @OA\Items(type="string"), example={"flour", "sugar"}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Recipe created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="New Recipe"),
     *             @OA\Property(property="unique_code", type="string", example="REC123"),
     *             @OA\Property(property="chef_id", type="integer", example=1),
     *             @OA\Property(property="ingredients", type="array", @OA\Items(type="string"), example={"flour", "sugar"}),
     *             @OA\Property(property="likes_count", type="integer", example=0),
     *             @OA\Property(property="dislikes_count", type="integer", example=0),
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
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unique_code' => 'required|string|unique:recipes',
            'chef_id' => 'required|exists:chefs,id',
        ]);

        $recipe = Recipe::create($request->all());
        return response()->json($recipe, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/recipes/{id}",
     *     summary="Get a recipe by ID",
     *     tags={"Recipes"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Recipe ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recipe details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Spaghetti Carbonara"),
     *             @OA\Property(property="unique_code", type="string", example="REC123"),
     *             @OA\Property(property="chef_id", type="integer", example=1),
     *             @OA\Property(property="ingredients", type="array", @OA\Items(type="string"), example={"flour", "sugar"}),
     *             @OA\Property(property="likes_count", type="integer", example=10),
     *             @OA\Property(property="dislikes_count", type="integer", example=2),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recipe not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Recipe not found."),
     *         ),
     *     ),
     * )
     */
    public function show(string $id)
    {
        $recipe = Recipe::findOrFail($id);
        return response()->json($recipe);
    }

    /**
     * @OA\Put(
     *     path="/api/recipes/{id}",
     *     summary="Update a recipe by ID",
     *     tags={"Recipes"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Recipe ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "unique_code", "chef_id"},
     *             @OA\Property(property="name", type="string", example="Updated Recipe"),
     *             @OA\Property(property="unique_code", type="string", example="REC456"),
     *             @OA\Property(property="chef_id", type="integer", example=1),
     *             @OA\Property(property="ingredients", type="array", @OA\Items(type="string"), example={"flour", "sugar"}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recipe updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Updated Recipe"),
     *             @OA\Property(property="unique_code", type="string", example="REC456"),
     *             @OA\Property(property="chef_id", type="integer", example=1),
     *             @OA\Property(property="ingredients", type="array", @OA\Items(type="string"), example={"flour", "sugar"}),
     *             @OA\Property(property="likes_count", type="integer", example=10),
     *             @OA\Property(property="dislikes_count", type="integer", example=2),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recipe not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Recipe not found."),
     *         ),
     *     ),
     * )
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unique_code' => 'required|string|unique:recipes,unique_code,' . $id,
            'chef_id' => 'required|exists:chefs,id',
        ]);

        $recipe = Recipe::findOrFail($id);
        $recipe->update($request->all());
        return response()->json($recipe);
    }

    /**
     * @OA\Delete(
     *     path="/api/recipes/{id}",
     *     summary="Delete a recipe by ID",
     *     tags={"Recipes"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Recipe ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recipe deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Recipe deleted successfully"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recipe not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Recipe not found."),
     *         ),
     *     ),
     * )
     */
    public function destroy(string $id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Post(
     *     path="/api/recipes/{recipe}/add-to-collection",
     *     summary="Add a recipe to a collection",
     *     tags={"Recipes"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="recipe",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the recipe"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"collection_id"},
     *             @OA\Property(property="collection_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recipe added to collection successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Recipe added to collection successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid.")
     *         )
     *     )
     * )
     */
    public function addToCollection(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'collection_id' => 'required|exists:collections,id',
        ]);

        $collection = Collection::findOrFail($validated['collection_id']);
        $recipe->collections()->attach($collection);

        return response()->json(['message' => 'Recipe added to collection successfully'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/recipes/not-in-collection/{ingredient}",
     *     summary="Find recipes not in any collection by ingredient",
     *     tags={"Recipes"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="ingredient",
     *         in="path",
     *         description="Ingredient name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of recipes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Spaghetti Carbonara"),
     *                 @OA\Property(property="unique_code", type="string", example="REC123"),
     *                 @OA\Property(property="chef_id", type="integer", example=1),
     *                 @OA\Property(property="ingredients", type="array", @OA\Items(type="string"), example={"flour", "sugar"}),
     *                 @OA\Property(property="likes_count", type="integer", example=10),
     *                 @OA\Property(property="dislikes_count", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No recipes found for the given ingredient",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No recipes found for the given ingredient."),
     *         ),
     *     ),
     * )
     */
    public function findRecipeNotInCollectionByIngredient(Request $request)
    {
        $request->validate([
            'ingredient' => 'required|string',
        ]);

        $ingredient = $request->input('ingredient');

        $recipes = Recipe::whereJsonContains('ingredients', $ingredient)
            ->doesntHave('collections')
            ->get();

        return response()->json($recipes);
    }

    /**
     * @OA\Post(
     *     path="/api/recipes/{id}/like",
     *     summary="Like a recipe",
     *     tags={"Recipes"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the recipe",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recipe liked successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Recipe liked successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recipe not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Recipe not found."),
     *         ),
     *     ),
     * )
     */
    public function like($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->increment('likes_count');
        $recipe->save();

        return response()->json(['message' => 'Recipe liked successfully']);
    }

    /**
     * @OA\Post(
     *     path="/api/recipes/{id}/dislike",
     *     summary="Dislike a recipe",
     *     tags={"Recipes"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the recipe",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recipe disliked successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Recipe disliked successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recipe not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Recipe not found."),
     *         ),
     *     ),
     * )
     */
    public function dislike($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->increment('dislikes_count');
        $recipe->save();

        return response()->json(['message' => 'Recipe disliked successfully']);
    }
}
