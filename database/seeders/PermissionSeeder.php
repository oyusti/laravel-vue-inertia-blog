<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage Dashboard',
            'manage Users',
            'manage Roles',
            'manage Permissions',
            'manage Categories',
            'manage Tags',
            'manage Posts'
        ];

        foreach($permissions as $permission){
            Permission::create([
                'name' => $permission
            ]);
        }
    }
}
