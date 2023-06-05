<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "administrator",
            'email' => config('app.initial_email'),
            'password' => Hash::make(config('app.initial_passwordhash')),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'email_verified_at' => now(),
        ]);

    }
}
