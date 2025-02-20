<?php

namespace Config;

class MyRules
{

    public function time_check($value, $params, $data): bool
    {
        $max_time = $data['max_attend_time'];
        $min_time = $data['min_attend_time'];
        if (strtotime($min_time) >= strtotime($max_time)) {
            $error = lang('Drivers.time_check');
            $error = 'عفوا ، يجب أن يقل الحد الأدنى للحضور عن الحد الأقصى';
            return false;
        }
        return true;
    }
}
