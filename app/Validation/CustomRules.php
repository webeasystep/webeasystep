<?php

namespace App\Validation;

class CustomRules
{
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
