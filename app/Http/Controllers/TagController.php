<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class TagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tags",
     *     tags={"Tags"},
     *     summary="Get all tags",
     *     description="Returns a list of all tags.",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="A list of tags",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Tag Name"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z")
     *             )
     *         )
     *     ),
     * )
     */
    public function index()
    {
        return Tag::all();
    }

    /**
     * @OA\Post(
     *     path="/api/tags",
     *     tags={"Tags"},
     *     summary="Create a new tag",
     *     description="Creates a new tag with the provided name.",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="New Tag")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="The created tag",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="New Tag"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tags|max:255',
        ]);

        $tag = Tag::create([
            'name' => $request->name,
        ]);

        return response()->json($tag, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/tags/{tag}",
     *     tags={"Tags"},
     *     summary="Get tag details",
     *     description="Returns details of a specific tag.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="tag",
     *         in="path",
     *         required=true,
     *         description="Tag ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Tag Name"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tag not found.")
     *         )
     *     ),
     * )
     */
    public function show(Tag $tag)
    {
        return $tag;
    }

    /**
     * @OA\Put(
     *     path="/api/tags/{tag}",
     *     tags={"Tags"},
     *     summary="Update a tag",
     *     description="Updates an existing tag with the provided name.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="tag",
     *         in="path",
     *         required=true,
     *         description="Tag ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Tag")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The updated tag",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Updated Tag"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T12:00:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tag not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     * )
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|unique:tags,name,' . $tag->id . '|max:255',
        ]);

        $tag->update([
            'name' => $request->name,
        ]);

        return response()->json($tag, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/tags/{tag}",
     *     tags={"Tags"},
     *     summary="Delete a tag",
     *     description="Deletes an existing tag.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="tag",
     *         in="path",
     *         required=true,
     *         description="Tag ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tag not found.")
     *         )
     *     ),
     * )
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Post(
     *     path="/api/tags/{tag}/recipe/{recipe}",
     *     tags={"Tags"},
     *     summary="Add a tag to a recipe",
     *     description="Associates a tag with a specific recipe.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="recipe",
     *         in="path",
     *         required=true,
     *         description="Recipe ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="tag",
     *         in="path",
     *         required=true,
     *         description="Tag ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag added to recipe successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tag added to recipe successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recipe or Tag not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Recipe or Tag not found.")
     *         )
     *     ),
     * )
     */
    public function addTagToRecipe($recipeId, $tagId)
    {
        $recipe = Recipe::findOrFail($recipeId);
        $tag = Tag::findOrFail($tagId);

        if ($recipe->tags()->where('tag_id', $tagId)->exists()) {
            return response()->json(['message' => 'Tag already added to recipe.'], 200);
        }

        $recipe->tags()->attach($tagId);

        return response()->json(['message' => 'Tag added to recipe successfully.'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/tags/{tag}/recipes",
     *     tags={"Tags"},
     *     summary="Fetch recipes by tag",
     *     description="Fetches all recipes associated with a specific tag.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="tag",
     *         in="path",
     *         required=true,
     *         description="Tag ID",
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
     *         description="Tag not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tag not found.")
     *         )
     *     ),
     * )
     */
    public function getRecipesByTag($tagId)
    {
        $tag = Tag::findOrFail($tagId);

        $recipes = $tag->recipes;

        return response()->json($recipes, 200);
    }
}
