<?php

use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Jcove\Admin\Models\AdminUser::create([
        'username'  => 'admin',
        'password'  => bcrypt('admin'),
        'name'      => 'Administrator',
    ]);
    }
}
