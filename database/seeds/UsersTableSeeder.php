<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;
use TCG\Voyager\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() == 0) {
            $role = Role::where('name', 'admin')->firstOrFail();

            User::create([
                'name'           => 'Admin',
                'username'           => 'admin',
                'phone_number'   => '+996 702 77 23 17',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $role->id,
            ]);
        }
    }
}
