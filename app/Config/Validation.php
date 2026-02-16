<?php

namespace Config;

use App\Validation\CustomRules;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
     //   CreditCardRules::class,
        CustomRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
        'my_list' => 'admin_layout\errors_list',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    
    /**
     * Registration validation rules for user registration
     * Both email and mobile are now required
     */
    public array $registrationRules = [
        'full_name' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[auth_identities.secret]',
        //'country_code' => 'required|valid_country_code',
        'mobile' => 'required',
        'password' => 'required|min_length[6]',
        'password_confirm' => 'required|matches[password]',
    ];
}
