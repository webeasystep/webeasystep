<?php

namespace App\Validation;

class CustomRules
{
    /**
     * Validates Egyptian mobile number format
     * 
     * @param string $value The mobile number to validate
     * @param string $params Additional parameters (not used)
     * @param array $data Form data array (optional)
     * @return bool True if valid Egyptian mobile number
     */
    public function egyptian_mobile($value, $params, $data = []): bool
    {
        // Remove any spaces, dashes, or plus signs
        $cleanValue = preg_replace('/[\s\-\+]/', '', $value);
        
        // Check if it matches Egyptian mobile pattern: 01xxxxxxxxx (11 digits total)
        return preg_match('/^01[0-9]{9}$/', $cleanValue) === 1;
    }

    public function time_check($value, $params, $data): bool
    {

        $max_time = $data['open_time'];
        $min_time = $data['close_time'];

        if (strtotime($min_time) >= strtotime($max_time)) {
            return false;
        }
        return true;
    }

    public function after($value, $fields, $data): bool
    {
        $startDate = $data[$fields] ?? null;
        if (!$startDate) {
            return false;
        }

        return strtotime($value) > strtotime($startDate);
    }
}
