<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Helpcenter\Tag;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Helpcenter\Tag> */
class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'user_id' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
