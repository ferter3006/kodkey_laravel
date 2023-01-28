<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Rule;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Ferter',
            'email' => 'fer',
            'password' => 'fer'
        ]);

        User::create([
            'name' => 'Oponente',
            'email' => 'opo',
            'password' => 'opo'
        ]);

        Rule::create([]);
    }
}
