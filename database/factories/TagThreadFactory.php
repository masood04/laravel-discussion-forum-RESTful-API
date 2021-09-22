<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\TagThread;
use App\Models\Thread;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagThreadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TagThread::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
//   'tag_id' => Tag::factory()->create()->id,
            'tag_id' => $this->faker->numberBetween(1, 7),
            'thread_id' => Thread::factory()->create()->id,

        ];
    }
}
