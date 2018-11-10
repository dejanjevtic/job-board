<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([[
            'name' => 'ROLE_MODERATOR',
            'description' => 'Job Board Moderator Privilege',
        ], 
        [
            'name' => 'ROLE_HR',
            'description' => 'HR',
        ]]);
    }
}
