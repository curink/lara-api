<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\{Role,Permission};
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        $admin = User::create([
            'nik' => $faker->nik(),
            'name' => $faker->name(),
            'address' => $faker->address(),
            'email' => 'admin@example.com',
            'phone' => $faker->phoneNumber(),
            'email_verified_at' => now(),
            'password' => '00000000',
            'remember_token' => Str::random(10),
        ]);

        $role = Role::findById(1, 'api');
        $admin->syncRoles($role);

        $permissions =[
            'user-list',
            'user-detail',
            'user-create',
            'user-update'
        ];
        foreach($permissions as $permission){
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }
        $role->syncPermissions(Permission::all());
    }
}
