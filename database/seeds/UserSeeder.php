<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'fname' => 'Jimmy',
            'lname' => 'Parker',
            'username' => 'jimmy',
            'password' => bcrypt('admin123'),
            'level' => 'admin',
            'status' => 1
        ]);

        DB::table('users')->insert([
            'fname' => 'Michell',
            'lname' => 'Sejismundo',
            'username' => 'michell',
            'password' => bcrypt('michell'),
            'level' => 'admin',
            'status' => 1
        ]);
    }
}
