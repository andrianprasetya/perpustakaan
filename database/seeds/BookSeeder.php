<?php

use App\Book;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Book::query()->create([
            'name' => "TestBook",
            'description' => "TestDescriptionBook",
            'is_active' => 1,
            'created_at' => Carbon::now(),
        ]);

        \App\Book::query()->create([
            'name' => "BukuStore",
            'description' => "Dekripsi Buku",
            'is_active' => 0,
            'created_at' => Carbon::now(),
        ]);
    }
}
