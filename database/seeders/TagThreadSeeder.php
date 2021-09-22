<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Thread;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag = Tag::factory()->count(4)->create();
        DB::table('tag_threads')
            ->insert([
                'tag_id' => 2,
                'thread_id' => 3,

            ]);
    }
}
