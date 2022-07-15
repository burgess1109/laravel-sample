<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['code' => 'user', 'action' => 'create'],
            ['code' => 'user', 'action' => 'read'],
            ['code' => 'user', 'action' => 'update'],
            ['code' => 'user', 'action' => 'delete'],
            ['code' => 'role', 'action' => 'create'],
            ['code' => 'role', 'action' => 'read'],
            ['code' => 'role', 'action' => 'update'],
            ['code' => 'role', 'action' => 'delete'],
            ['code' => 'permission', 'action' => 'create'],
            ['code' => 'permission', 'action' => 'read'],
            ['code' => 'permission', 'action' => 'update'],
            ['code' => 'permission', 'action' => 'delete'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
