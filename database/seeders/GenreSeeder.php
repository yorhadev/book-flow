<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            'fiction',
            'romance',
            'fantasy',
            'adventure',
            'mystery',
            'horror',
            'drama',
            'poetry',
            'history',
            'biography',
            'science',
            'technology',
            'self-help'
        ];

        foreach ($genres as $genre) {
            Genre::create(['name' => $genre]);
        }
    }
}
