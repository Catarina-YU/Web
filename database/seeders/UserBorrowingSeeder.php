<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use Illuminate\Database\Seeder;

class UserBorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(10)->create()->each(function ($user) {
            Borrowing::factory(rand(1, 5))->create([
                'user_id' => $user->id,
                'book_id' => Book::inRandomOrder()->first()->id,
            ]);
        });
    }
}
