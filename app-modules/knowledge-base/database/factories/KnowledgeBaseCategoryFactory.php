<?php

namespace Assist\KnowledgeBase\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Assist\KnowledgeBase\Models\KnowledgeBaseCategory;

/**
 * @extends Factory<KnowledgeBaseCategory>
 */
class KnowledgeBaseCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
