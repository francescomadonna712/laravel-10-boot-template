<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        if (!User::where("email", "luca@lambia.it")->first()) {
            $mainUser = new User();
            $mainUser->name = "Luca";
            $mainUser->email = "luca@lambia.it";
            $mainUser->password = Hash::make('1backdoor2big');
            $mainUser->save();
        }
    }
}
