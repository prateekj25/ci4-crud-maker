<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use App\Models\RoleModel;
use App\Models\PermissionModel;
use App\Models\UserRoleModel;

class RBACSeeder extends Seeder
{
    public function run()
    {
        $roleModel = new RoleModel();
        $permissionModel = new PermissionModel();
        $userRoleModel = new UserRoleModel();
        $db = \Config\Database::connect();

        // 1. Create Roles
        $roles = [
            ['name' => 'super-admin', 'title' => 'Super Admin', 'description' => 'Full Access'],
            ['name' => 'admin', 'title' => 'Administrator', 'description' => 'Manage content'],
            ['name' => 'user', 'title' => 'User', 'description' => 'Standard user'],
        ];

        $roleMap = []; // name => id
        foreach ($roles as $role) {
            $existing = $roleModel->where('name', $role['name'])->first();
            if (!$existing) {
                $roleModel->insert($role);
                $roleMap[$role['name']] = $roleModel->getInsertID();
            } else {
                $roleMap[$role['name']] = $existing->id;
            }
        }

        // 2. Create Permissions
        $modules = ['users', 'roles', 'permissions', 'menus', 'modules'];
        $actions = ['create', 'read', 'update', 'delete'];

        $permissionIds = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $name = "{$module}.{$action}";
                $title = ucfirst($action) . " " . ucfirst($module);

                $existing = $permissionModel->where('name', $name)->first();
                if (!$existing) {
                    $permissionModel->insert([
                        'name' => $name,
                        'title' => $title,
                        'description' => "Can {$action} {$module}"
                    ]);
                    $permissionIds[] = $permissionModel->getInsertID();
                } else {
                    $permissionIds[] = $existing->id;
                }
            }
        }

        // Additional Special Permissions
        $specialPerms = [
            ['name' => 'dashboard.access', 'title' => 'Access Dashboard'],
            ['name' => 'settings.manage', 'title' => 'Manage Settings'],
        ];

        foreach ($specialPerms as $perm) {
            $existing = $permissionModel->where('name', $perm['name'])->first();
            if (!$existing) {
                $permissionModel->insert($perm);
                $permissionIds[] = $permissionModel->getInsertID();
            } else {
                $permissionIds[] = $existing->id;
            }
        }

        // 3. Assign Permissions to Roles (Super Admin gets ALL)
        // Clear existing permissions for super-admin to avoid duplicates/cleanup
        $superAdminId = $roleMap['super-admin'];
        $db->table('roles_permissions')->where('role_id', $superAdminId)->delete();

        $rolePermissions = [];
        foreach ($permissionIds as $permId) {
            $rolePermissions[] = [
                'role_id' => $superAdminId,
                'permission_id' => $permId
            ];
        }
        $db->table('roles_permissions')->insertBatch($rolePermissions);


        // 4. Create Super Admin User
        $userModel = new \App\Models\UserModel();
        $email = 'admin@admin.com';
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $user = new User([
                'username' => 'superadmin',
                'email' => $email,
                'password' => 'password123',
                'active' => 1
            ]);

            // Shield's magic save that handles identity
            $userModel->save($user);
            $userId = $userModel->getInsertID();

            // Force activation if Shield config requires it
            $db->table('users')->where('id', $userId)->update(['active' => 1]);

            CLI::write("Super Admin Created: {$email}", 'green');
        } else {
            $userId = $user->id;
            CLI::write("Super Admin Exists: {$email}", 'yellow');
        }

        // 5. Assign Role to User
        $existingLink = $userRoleModel->where('user_id', $userId)->where('role_id', $superAdminId)->first();
        if (!$existingLink) {
            $userRoleModel->insert([
                'user_id' => $userId,
                'role_id' => $superAdminId
            ]);
        }
    }
}
