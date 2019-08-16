<?php

use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('divisions')->insert([
            'name' => 'Nursing'
        ]);

        DB::table('divisions')->insert([
            'name' => 'Medical'
        ]);

        DB::table('divisions')->insert([
            'name' => 'Ancillary'
        ]);
    }
}
