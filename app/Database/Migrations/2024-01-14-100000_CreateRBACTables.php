<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRBACTables extends Migration
{
    public function up()
    {
        // Roles Table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255], // slug: superadmin
            'title' => ['type' => 'VARCHAR', 'constraint' => 255], // Human readable: Super Admin
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('roles');

        // Permissions Table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255], // slug: users.create
            'title' => ['type' => 'VARCHAR', 'constraint' => 255], // Human readable: Create Users
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('permissions');

        // Roles Permissions Pivot Table
        $this->forge->addField([
            'role_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'permission_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey(['role_id', 'permission_id']);
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('permission_id', 'permissions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('roles_permissions');

        // Modules Table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'module_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'module_slug' => ['type' => 'VARCHAR', 'constraint' => 255],
            'table_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'controller_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'icon' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'fas fa-cube'],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('modules');

        // Menus Table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'module_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'parent_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'icon' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'far fa-circle'],
            'route' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'order' => ['type' => 'INT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('module_id', 'modules', 'id', 'CASCADE', 'SET NULL');
        // Self-referencing foreign key for nested menus
        $this->forge->addForeignKey('parent_id', 'menus', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('menus');
    }

    public function down()
    {
        $this->forge->dropTable('menus');
        $this->forge->dropTable('modules');
        $this->forge->dropTable('roles_permissions');
        $this->forge->dropTable('permissions');
        $this->forge->dropTable('roles');
    }
}
