<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Developer',
            'email' => env('ADMIN_EMAIL'),
            'email_verified_at' => now(),
            'password' => bcrypt(env('ADMIN_PASSWORD')),
            'remember_token' => Str::random(10),
            'document' => '',
            'admin' => 1,
        ]);
    }
}
