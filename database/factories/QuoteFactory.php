<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Quote;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quote = $this->faker->sentence();
        $wordCount = str_word_count($quote);
        return [
            //
            'quote' => $quote,
            'author' => $this->faker->name(),
            'source' => $this->faker->sentence(),
            'word_count' => $wordCount,
            'view_count' => $this->faker->numberBetween(1, 100),
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
