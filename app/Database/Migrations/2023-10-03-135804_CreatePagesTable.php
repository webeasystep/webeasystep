<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreatePagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'auto_increment' => true,],
            'page_link' => [  'type' => 'VARCHAR',  'constraint' => 255, ],
            'title_ar' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true,],
            'title_en' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true,],
            'desc_ar' => [ 'type' => 'TEXT','null' => true,],
            'desc_en' => [ 'type' => 'TEXT','null' => true,],
            'content_ar' => [ 'type' => 'TEXT', 'null' => true,],
            'content_en' => [ 'type' => 'TEXT','null' => true,],
            'created_at' => [ 'type'=> 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP'),],
            'deleted_at' => [ 'type'=> 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP'),],
            'updated_at' => [ 'type'=> 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP'),],
            'active' => ['type' => 'TINYINT',  'constraint' => 1, 'default' => 0,],
            'show_home' => ['type' => 'TINYINT','constraint' => 1,'default' => 0,],
            'images' => ['type' => 'JSON', 'null' => true, ],
            'sort' => [ 'type' => 'INT','default' => 0,],
            'parent_id' => [ 'type' => 'INT', 'default' => 0,],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('pages');
    }

    public function down()
    {
        $this->forge->dropTable('pages');
    }
}
