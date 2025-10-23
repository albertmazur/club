<?php

namespace Database\Factories;

use App\Enums\Language;
use App\Models\Stadium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = [];
        $description = [];
        foreach (Language::cases() as $lang)
        {
            $name[$lang->value] = fake($lang->value)->company();
            $description[$lang->value] = fake($lang->value)->realText(180);
        }

        return [
            'name' => $name,
            'description' => $description,
            'date' => fake()->dateTimeBetween('-2 years', '+2 years')->format('Y-m-d'),
            'time' => fake()->time(),
            'price' =>fake()->randomFloat(2, 20, 200),
            'stadium_id' => Stadium::factory()
        ];
    }
}
