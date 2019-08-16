<?php

use Illuminate\Database\Seeder;

class AdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user')->insert([
            'fname' => 'Jimmy',
            'lname' => 'Lomocso',
            'designation' => 1,
            'contact' => '0916 207 2427',
            'email' => 'jimmy.tdh@gmail.com',
            'sex' => 'Male',
            'dob' => '1990-09-23',
            'section' => 1,
            'address' => 'Guadalupe, Cebu City',
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'level' => 'admin',
            'status' => 1,
            'picture' => 'default.png'
        ]);

        DB::table('section')->insert([
            'initial' => 'IT',
            'code' => 'IHOMP',
            'division_id' => '1',
            'description' => 'Integrated Hospital Operation and Management Program'
        ]);

        DB::table('designation')->insert([
            'code' => 'CMT II',
            'description' => 'Computer Maintenance Technologist II'
        ]);

        DB::table('division')->insert([
            'code' => 'MCC',
            'description' => 'Medical Center Chief'
        ]);
    }
}
