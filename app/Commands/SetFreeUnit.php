<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SetFreeUnit extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'unit:set-free';
    protected $description = 'Set a unit as free for testing enrollment flow';

    public function run(array $params)
    {
        $unitId = $params[0] ?? null;
        
        if (!$unitId) {
            CLI::write('Please provide a unit ID. Usage: php spark unit:set-free <unit_id>', 'red');
            return;
        }

        // Load models
        $unitsModel = new \Modules\Units\Models\UnitsModel();
        
        // Check if unit exists
        $unit = $unitsModel->find($unitId);
        if (!$unit) {
            CLI::write("Unit with ID {$unitId} not found.", 'red');
            return;
        }

        // Update unit to be free
        try {
            $updated = $unitsModel->set(['is_free' => 1])->where('id', $unitId)->update();
            
            if ($updated) {
                CLI::write("Unit '{$unit->unit_name}' (ID: {$unitId}) has been set as free.", 'green');
                CLI::write("You can now test the free enrollment flow with this unit.", 'yellow');
            } else {
                CLI::write("Failed to update unit {$unitId}.", 'red');
            }
        } catch (\Exception $e) {
            CLI::write("Error updating unit: " . $e->getMessage(), 'red');
        }
    }
}