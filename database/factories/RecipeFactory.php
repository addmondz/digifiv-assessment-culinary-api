<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'unique_code' => $this->faker->unique()->word,
            'chef_id' => function () {
                return \App\Models\Chef::factory()->create()->id;
            },
            'ingredients' => json_encode([
                ['name' => 'Ingredient 1', 'quantity' => '100g'],
                ['name' => 'Ingredient 2', 'quantity' => '200g'],
            ]),
            'likes_count' => $this->faker->numberBetween(0, 100),
            'dislikes_count' => $this->faker->numberBetween(0, 50),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
