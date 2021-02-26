<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role,Permission};

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'super-admin',
            'admin',
            'kasir'
        ];

        foreach($roles as $role){
            Role::create(['name' => $role, 'guard_name' => 'api']);
        }
    }
}
