<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUsersTable extends Migration
{
    public function up()
    {
        // add users table
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username' => ['type' => 'varchar', 'constraint' => 30, 'null' => true],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['male', 'female'],
                'default' => 'male',
            ],
            'avatar' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => true,
            ],
            'address_ar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'address_en' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status_message' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true,],
            'active' => ['type' => 'tinyint', 'constraint' => 1, 'null' => 0, 'default' => 0],
            'last_active' => ['type' => 'datetime', 'null' => true],
            'type'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('users');

        // add  auth_identities table
        $this->forge->addField([
            'id'           => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'type'         => ['type' => 'varchar', 'constraint' => 255],
            'name'         => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'secret'       => ['type' => 'varchar', 'constraint' => 255],
            'secret2'      => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'expires'      => ['type' => 'datetime', 'null' => true],
            'extra'        => ['type' => 'text', 'null' => true],
            'force_reset'  => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
            'last_used_at' => ['type' => 'datetime', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
            'updated_at'   => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('user_id');
        $this->forge->addUniqueKey(['type', 'secret']);
        $this->forge->createTable('auth_identities');

        // add  auth_logins table
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'ip_address' => ['type' => 'varchar', 'constraint' => 255],
            'user_agent' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'id_type'    => ['type' => 'varchar', 'constraint' => 255],
            'identifier' => ['type' => 'varchar', 'constraint' => 255],
            'user_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true], // Only for successful logins
            'date'       => ['type' => 'datetime'],
            'success'    => ['type' => 'tinyint', 'constraint' => 1],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['id_type', 'identifier']);
        $this->forge->addKey('user_id');
        $this->forge->createTable('auth_logins');

        // add  auth_token_logins table
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'ip_address' => ['type' => 'varchar', 'constraint' => 255],
            'user_agent' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'id_type'    => ['type' => 'varchar', 'constraint' => 255],
            'identifier' => ['type' => 'varchar', 'constraint' => 255],
            'user_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true], // Only for successful logins
            'date'       => ['type' => 'datetime'],
            'success'    => ['type' => 'tinyint', 'constraint' => 1],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['id_type', 'identifier']);
        $this->forge->addKey('user_id');
        $this->forge->createTable('auth_token_logins');

        $this->forge->addField([
            'id'              => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'selector'        => ['type' => 'varchar', 'constraint' => 255],
            'hashedValidator' => ['type' => 'varchar', 'constraint' => 255],
            'user_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'expires'         => ['type' => 'datetime'],
            'created_at'      => ['type' => 'datetime', 'null' => false],
            'updated_at'      => ['type' => 'datetime', 'null' => false],
        ]);

        // add  auth_remember_tokens table
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('selector'); // Unique constraint
        $this->forge->addForeignKey('user_id', 'users', 'id', '', 'CASCADE');
        $this->forge->createTable('auth_remember_tokens');

        // add  auth_groups table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'group_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'default' => null,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('group_name'); // Unique constraint
        $this->forge->createTable('auth_groups');

        // add  auth_groups_users table
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'group'      => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'created_at' => ['type'    => 'TIMESTAMP','default' => new RawSql('CURRENT_TIMESTAMP'),],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', '', 'CASCADE');
        $this->forge->createTable('auth_groups_users');

        // add  auth_permissions table
        $this->forge->addField([
            'id' => [ 'type' => 'INT','auto_increment' => true, ],
            'permission_name' => [ 'type' => 'VARCHAR','constraint' => 100,'null' => true,],
            'title' => ['type' => 'VARCHAR','constraint' => 100, ],
            'updated_at' => ['type'    => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP'), ],
            'created_at' => ['type'    => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP'),],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('auth_permissions');

        // add  auth_permissions_users table
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'permission' => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
            'group_id' => [
                'type' => 'INT',
                'null' => true,
            ],

        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('group_id', 'auth_groups', 'id', 'CASCADE', 'CASCADE'); // Add foreign key reference
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE'); // Add foreign key reference
        $this->forge->createTable('auth_permissions_users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
        $this->forge->dropTable('auth_identities');

        $this->forge->dropTable('auth_logins');
        $this->forge->dropTable('auth_remember_tokens');
        $this->forge->dropTable('auth_token_logins');

        $this->forge->dropTable('auth_groups');
        $this->forge->dropTable('auth_groups_users');

        $this->forge->dropTable('auth_permissions');
        $this->forge->dropTable('auth_permissions_users');

    }
}

/*
    php spark migrate --exclude=2020-12-28-223112,2021-07-04-041948,2021-11-14-143905
*/
