<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class AuthorPublisherBookSeeder extends Seeder
{
    public function run(): void
    {
        Author::factory(100)->create()->each(function ($author) {
            $publisher = Publisher::factory()->create();

            $author->books()->createMany(
                Book::factory(10)->make([
                    'url_image' => '',
                    'category_id' => Category::inRandomOrder()->first()->id,
                    'publisher_id' => $publisher->id
                ])->toArray()
            );
        });
    }
}
