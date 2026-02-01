<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("cards")->insert([
            [
                "image" => "frajola.png",
                "rarity" => "comum",
                "title" => "Frajola"
            ],
            [
                "image" => "laranja.png",
                "rarity" => "comum",
                "title" => "Laranja"
            ],
            [
                "image" => "siames.png",
                "rarity" => "raro",
                "title" => "Siâmes"
            ],
            [
                "image" => "mago.png",
                "rarity" => "epíco",
                "title" => "Gato Mago"
            ],
            [
                "image" => "kittytros.png",
                "rarity" => "lendário",
                "title" => "Kittytros"
            ],

        ]);
    }
}
