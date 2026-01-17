<?php

namespace App\Validation;

class CustomRules
{
    /**
     * Validates Egyptian mobile number format (legacy support)
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

    /**
     * Validates mobile number format (international, works with any country)
     * Mobile number should be 7-15 digits (local number without country code)
     * 
     * @param string $value The mobile number to validate
     * @param string $params Additional parameters (not used)
     * @param array $data Form data array (optional)
     * @return bool True if valid mobile number
     */
    public function valid_mobile($value, $params, $data = []): bool
    {
        // Remove any spaces and dashes
        $cleanValue = preg_replace('/[\s\-]/', '', $value);
        
        // Remove leading zero if present
        if (str_starts_with($cleanValue, '0')) {
            $cleanValue = substr($cleanValue, 1);
        }
        
        // Should be 7-15 digits
        return preg_match('/^[0-9]{7,15}$/', $cleanValue) === 1;
    }

    /**
     * Validates country code format
     * Should be + followed by 1-4 digits
     * 
     * @param string $value The country code to validate
     * @param string $params Additional parameters (not used)
     * @param array $data Form data array (optional)
     * @return bool True if valid country code
     */
    public function valid_country_code($value, $params, $data = []): bool
    {
        return preg_match('/^\+[0-9]{1,4}$/', $value) === 1;
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
