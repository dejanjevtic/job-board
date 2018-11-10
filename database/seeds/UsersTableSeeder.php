<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'MODERATOR',
            'email' => 'dejan_d_jevtic@yahoo.com',
            'email_verified_at' => 1,
            'password' => bcrypt('Dejan123$'),
        ]);
    }
}
