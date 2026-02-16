<?php

/**
 * Countries Helper
 * Provides country codes list and mobile formatting functions
 */

if (!function_exists('get_country_codes')) {
    /**
     * Get list of Arab country codes
     * @return array
     */
    function get_country_codes(): array
    {
        return [
            ['code' => '+20', 'country' => 'مصر', 'country_en' => 'Egypt', 'flag' => '🇪🇬', 'iso' => 'EG'],
            ['code' => '+966', 'country' => 'السعودية', 'country_en' => 'Saudi Arabia', 'flag' => '🇸🇦', 'iso' => 'SA'],
            ['code' => '+971', 'country' => 'الإمارات', 'country_en' => 'UAE', 'flag' => '🇦🇪', 'iso' => 'AE'],
            ['code' => '+965', 'country' => 'الكويت', 'country_en' => 'Kuwait', 'flag' => '🇰🇼', 'iso' => 'KW'],
            ['code' => '+968', 'country' => 'عُمان', 'country_en' => 'Oman', 'flag' => '🇴🇲', 'iso' => 'OM'],
            ['code' => '+973', 'country' => 'البحرين', 'country_en' => 'Bahrain', 'flag' => '🇧🇭', 'iso' => 'BH'],
            ['code' => '+974', 'country' => 'قطر', 'country_en' => 'Qatar', 'flag' => '🇶🇦', 'iso' => 'QA'],
            ['code' => '+962', 'country' => 'الأردن', 'country_en' => 'Jordan', 'flag' => '🇯🇴', 'iso' => 'JO'],
            ['code' => '+961', 'country' => 'لبنان', 'country_en' => 'Lebanon', 'flag' => '🇱🇧', 'iso' => 'LB'],
            ['code' => '+964', 'country' => 'العراق', 'country_en' => 'Iraq', 'flag' => '🇮🇶', 'iso' => 'IQ'],
            ['code' => '+967', 'country' => 'اليمن', 'country_en' => 'Yemen', 'flag' => '🇾🇪', 'iso' => 'YE'],
            ['code' => '+963', 'country' => 'سوريا', 'country_en' => 'Syria', 'flag' => '🇸🇾', 'iso' => 'SY'],
            ['code' => '+218', 'country' => 'ليبيا', 'country_en' => 'Libya', 'flag' => '🇱🇾', 'iso' => 'LY'],
            ['code' => '+213', 'country' => 'الجزائر', 'country_en' => 'Algeria', 'flag' => '🇩🇿', 'iso' => 'DZ'],
            ['code' => '+212', 'country' => 'المغرب', 'country_en' => 'Morocco', 'flag' => '🇲🇦', 'iso' => 'MA'],
            ['code' => '+216', 'country' => 'تونس', 'country_en' => 'Tunisia', 'flag' => '🇹🇳', 'iso' => 'TN'],
            ['code' => '+249', 'country' => 'السودان', 'country_en' => 'Sudan', 'flag' => '🇸🇩', 'iso' => 'SD'],
        ];
    }
}

if (!function_exists('get_country_code_options')) {
    /**
     * Get country codes as HTML select options
     * @param string|null $selected Selected country code
     * @return string HTML options
     */
    function get_country_code_options(?string $selected = '+966'): string
    {
        $options = '';
        foreach (get_country_codes() as $country) {
            $isSelected = ($country['code'] === $selected) ? 'selected' : '';
            $options .= sprintf(
                '<option value="%s" %s>%s %s</option>',
                esc($country['code']),
                $isSelected,
                $country['flag'],
                esc($country['code'])
            );
        }
        return $options;
    }
}

if (!function_exists('format_mobile_display')) {
    /**
     * Format mobile number for display with flag and formatted number
     * @param string $fullMobile Full mobile number with country code
     * @return string Formatted display string
     */
    function format_mobile_display(string $fullMobile): string
    {
        if (empty($fullMobile)) {
            return '';
        }
        
        foreach (get_country_codes() as $country) {
            if (str_starts_with($fullMobile, $country['code'])) {
                $number = substr($fullMobile, strlen($country['code']));
                return $country['flag'] . ' ' . $country['code'] . ' ' . $number;
            }
        }
        
        // Fallback for legacy Egyptian numbers (01xxxxxxxx format)
        if (preg_match('/^01[0125][0-9]{8}$/', $fullMobile)) {
            return '🇪🇬 +20 ' . $fullMobile;
        }
        
        return $fullMobile;
    }
}

if (!function_exists('normalize_mobile')) {
    /**
     * Normalize mobile number to international format
     * @param string $mobile Mobile number (may or may not have country code)
     * @param string $countryCode Country code (e.g., '+20')
     * @return string Normalized mobile number
     */
    function normalize_mobile(string $mobile, string $countryCode = '+966'): string
    {
        // Remove spaces, dashes, and plus signs from mobile
        $mobile = preg_replace('/[\s\-\+]/', '', $mobile);
        
        // If mobile starts with 0, remove it (e.g., 01xxxxxxxx -> 1xxxxxxxx)
        if (str_starts_with($mobile, '0')) {
            $mobile = substr($mobile, 1);
        }
        
        // Ensure country code starts with +
        if (!str_starts_with($countryCode, '+')) {
            $countryCode = '+' . $countryCode;
        }
        
        return $countryCode . $mobile;
    }
}

if (!function_exists('extract_country_code')) {
    /**
     * Extract country code from a full mobile number
     * @param string $fullMobile Full mobile number with country code
     * @return array ['code' => country code, 'number' => local number]
     */
    function extract_country_code(string $fullMobile): array
    {
        foreach (get_country_codes() as $country) {
            if (str_starts_with($fullMobile, $country['code'])) {
                return [
                    'code' => $country['code'],
                    'number' => substr($fullMobile, strlen($country['code']))
                ];
            }
        }
        
        // Fallback for legacy Egyptian numbers
        if (preg_match('/^01[0125][0-9]{8}$/', $fullMobile)) {
            return [
                'code' => '+20',
                'number' => $fullMobile
            ];
        }
        
        return [
            'code' => '+966',
            'number' => $fullMobile
        ];
    }
}
