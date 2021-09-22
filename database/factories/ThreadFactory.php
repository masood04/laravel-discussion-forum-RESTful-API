<?php

namespace Database\Factories;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ThreadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Thread::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title =$this->faker->sentence(4);
        return [
            'title' => $title,
            'content' => $this->faker->sentence(8),
            'slug' => Str::slug($title),
            'solve' => $this->faker->numberBetween(0,1),
            'user_id' => User::factory()->create()->id,
            ];
    }
}
