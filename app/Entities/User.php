<?php

namespace App\Entities;

use CodeIgniter\Shield\Entities\User as ShieldUser;

/**
 * Custom User Entity extending Shield's User
 * 
 * @property string|null $full_name
 * @property string|null $mobile
 * @property string|null $gender
 * @property string|null $address
 * @property string|null $job_title_ar
 * @property string|null $job_title_en
 * @property string|null $avatar
 * @property string|null $user_type
 * @property int|null    $group_id
 */
class User extends ShieldUser
{
    // No need to override casts or dates - inherit from parent
}