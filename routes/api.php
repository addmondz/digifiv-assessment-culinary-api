<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\TagController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Grouping routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('chefs', ChefController::class);
    
    Route::get('recipes/not-in-collection/{ingredient}', [RecipeController::class, 'findRecipeNotInCollectionByIngredient']);
    Route::post('recipes/{recipe}/like', [RecipeController::class, 'like']);
    Route::post('recipes/{recipe}/dislike', [RecipeController::class, 'dislike']);
    Route::post('recipes/create', [RecipeController::class, 'create'])->middleware('chef');
    Route::apiResource('recipes', RecipeController::class);
    Route::post('recipes/{recipe}/add-to-collection', [RecipeController::class, 'addToCollection']);
    
    Route::apiResource('collections', CollectionController::class);

    Route::post('tags/{tag}/recipe/{recipes}', [TagController::class, 'addTagToRecipe']);
    Route::get('tags/{tag}/recipes', [TagController::class, 'getRecipesByTag']);
    Route::apiResource('tags', TagController::class);
});
