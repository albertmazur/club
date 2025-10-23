<?php

namespace Database\Factories;

use App\Enums\Language;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $content = [];
        foreach (Language::cases() as $lang)
        {
            $content[$lang->value] = fake($lang->value)->realText(180);
        }

        return [
            'content' => $content,
            'date' => fake()->date(),
            'time' => fake()->time(),
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
        ];
    }
}
