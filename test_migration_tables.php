<?php

require __DIR__ . '/vendor/autoload.php';

use Config\Database;

$db = Database::connect();

echo "Checking migration tables...\n\n";

// Check if tb_colleges exists
if ($db->tableExists('tb_colleges')) {
    echo "✓ tb_colleges table exists\n";
    $count = $db->table('tb_colleges')->countAllResults();
    echo "  - Records: $count\n";
} else {
    echo "✗ tb_colleges table does NOT exist\n";
}

// Check if tb_departments exists
if ($db->tableExists('tb_departments')) {
    echo "✓ tb_departments table exists\n";
    $count = $db->table('tb_departments')->countAllResults();
    echo "  - Records: $count\n";
    
    // Check structure
    $fields = $db->getFieldData('tb_departments');
    echo "  - Columns: " . count($fields) . "\n";
    foreach ($fields as $field) {
        echo "    * {$field->name} ({$field->type})\n";
    }
    
    // Check indexes
    $indexes = $db->getIndexData('tb_departments');
    echo "  - Indexes:\n";
    foreach ($indexes as $index) {
        $indexName = is_object($index) ? $index->name : $index['name'];
        echo "    * {$indexName}\n";
    }
    
    // Check foreign keys
    $foreignKeys = $db->getForeignKeyData('tb_departments');
    echo "  - Foreign Keys:\n";
    foreach ($foreignKeys as $fk) {
        echo "    * {$fk->constraint_name}: {$fk->column_name} -> {$fk->foreign_table_name}.{$fk->foreign_column_name}\n";
        echo "      ON DELETE: {$fk->on_delete}, ON UPDATE: {$fk->on_update}\n";
    }
} else {
    echo "✗ tb_departments table does NOT exist\n";
}

// Check if tb_courses has new columns
if ($db->tableExists('tb_courses')) {
    echo "\n✓ tb_courses table exists\n";
    $hasCollegeId = $db->fieldExists('college_id', 'tb_courses');
    $hasDepartmentId = $db->fieldExists('department_id', 'tb_courses');
    
    echo "  - college_id column: " . ($hasCollegeId ? "EXISTS" : "MISSING") . "\n";
    echo "  - department_id column: " . ($hasDepartmentId ? "EXISTS" : "MISSING") . "\n";
}

echo "\n";
