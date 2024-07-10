<?php

namespace Tests\Unit;

use App\Models\Chef;
use Tests\TestCase;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiEndpointTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_a_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJson(['message' => 'User registered successfully']);
    }


    // /** @test */
    public function it_can_log_in_a_user()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJson(['token_type' => 'Bearer']);
    }

    /** @test */
    public function it_can_log_out_a_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->postJson('api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }

    // /** @test */
    public function it_can_fetch_all_chefs()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        Chef::factory()->count(3)->create();

        $response = $this->get('/api/chefs');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    // /** @test */
    public function it_can_find_recipes_not_in_collection_by_ingredient()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/recipes/not-in-collection', [
            'ingredient_id' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'sugar'
                ]
            ]);
    }

    /** @test */
    public function it_can_like_a_recipe()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $recipe = Recipe::factory()->create();

        $k = Recipe::findOrFail($recipe->id);

        $response = $this->postJson("/api/recipes/{$recipe->id}/like");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Recipe liked successfully.']);
    }
}
