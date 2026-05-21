<?php

namespace Database\Factories;

use App\Enums\Tier;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<resource>
 */
class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->optional()->sentence(),
            'minimum_tier' => Tier::Silver,
            'is_active' => true,
        ];
    }

    public function minimumTier(Tier $tier): static
    {
        return $this->state(fn (array $attributes) => [
            'minimum_tier' => $tier,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
