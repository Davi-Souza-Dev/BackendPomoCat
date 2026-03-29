<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\FocusSession;
use App\Models\User;
use App\Models\UserCard;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        FocusSession::factory(20)->create();
        Card::factory(50)->create();
        UserCard::factory(20)->create();
    }
}
