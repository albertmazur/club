<?php

namespace Database\Factories;

use App\Enums\Language;
use App\Enums\ReasonSubmission;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $content = [];
        foreach (Language::cases() as $lang)
        {
            $content[$lang->value] = fake($lang->value)->realText(180);
        }

        return [
            'content' => $content,
            'reason' => fake()->randomElement(ReasonSubmission::cases()),
            'comment_id' => Comment::factory()
        ];
    }
}
