<?php

use LaraDev\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::where('id', '>', 0)->delete();
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@mail.com',
            'password' => 'admin',
        ]);
    }
}
