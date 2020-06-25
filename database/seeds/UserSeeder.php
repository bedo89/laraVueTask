<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create the first user with name: mohamed , email: mohamed@gmail.com , passowrd: 12345678
        User::create([
                'name' => 'mohamed',
                'email' => 'mohamed@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'user'
        ]);

        // create the second user with name: ahmed , email: ahmed@gmail.com , passowrd: 12345678
        User::create([
            'name' => 'ahmed',
            'email' => 'ahmed@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'user'
        ]);
    }
}
