<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'account' => 'admin01',
                'password' => password_hash('test', PASSWORD_DEFAULT),
                'email' => '',
                'role' => [
                    'name' => 'admin',
                    'note' => 'This is a role for admin'
                ],
                'permissions' => [
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
                ]
            ],
            [
                'account' => 'user01',
                'password' => password_hash('test', PASSWORD_DEFAULT),
                'email' => 'user01@test.com',
                'role' => [
                    'name' => 'user',
                    'note' => 'This is a role for user'
                ],
                'permissions' => [
                    ['code' => 'user', 'action' => 'create'],
                    ['code' => 'user', 'action' => 'read'],
                    ['code' => 'user', 'action' => 'update'],
                    ['code' => 'user', 'action' => 'delete'],
                ]
            ],
        ];

        foreach ($users as $user) {
            $role = Role::create([
                'name' => $user['role']['name'],
                'note' => $user['role']['note']
            ]);

            foreach ($user['permissions'] as $userPermission) {
                $permission = Permission::where('code', $userPermission['code'])
                    ->where('action', $userPermission['action'])->first();
                $role->permissions()->attach($permission->id);
            }

            User::create([
                'account' => $user['account'],
                'password' => $user['password'],
                'email' => $user['email'],
                'role_id' => $role->id,
            ]);
        }
    }
}
