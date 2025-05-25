<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin' => [
                'manage Dashboard',
                'manage Users',
                'manage Roles',
                'manage Permissions',
                'manage Categories',
                'manage Tags',
                'manage Posts'
            ],
            'blogger' => [
                'manage Dashboard',
                'manage Categories',
                'manage Posts'
            ]
        ];

        foreach($roles as $name => $permissions){
            $role = Role::create([
                'name' => $name
            ]);

            $role->syncPermissions($permissions);
        }
    }
}
