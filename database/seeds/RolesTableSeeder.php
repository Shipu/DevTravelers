<?php

use App\Models\BackpackUser;
use Backpack\PermissionManager\app\Models\Role;
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
        $roles = [
            'Admin',
            'Moderator',
            'Travelers'
        ];
        foreach ($roles as $role) {
            Role::create(
                [
                    'name'       => $role,
                    'guard_name' => 'backpack',
                ]
            );
        }
    }
}
