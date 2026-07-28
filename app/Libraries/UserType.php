<?php

declare(strict_types=1);

namespace App\Libraries;

use CodeIgniter\Shield\Entities\User;

/**
 * Centralizes user type values and redirects across the application.
 */
final class UserType
{
    public const STUDENT = 1;
    public const INSTRUCTOR = 2;

    /**
     * Normalizes any incoming value to a supported user type.
     */
    public static function normalize(mixed $value): int
    {
        return (int) $value === self::INSTRUCTOR ? self::INSTRUCTOR : self::STUDENT;
    }

    /**
     * Returns true when the provided user belongs to the instructor flow.
     */
    public static function isInstructor(?User $user): bool
    {
        return $user !== null && self::normalize($user->user_type ?? null) === self::INSTRUCTOR;
    }

    /**
     * Returns true when the provided user belongs to the student flow.
     */
    public static function isStudent(?User $user): bool
    {
        return ! self::isInstructor($user);
    }

    /**
     * Returns the default path for the provided user type.
     */
    public static function getDefaultPath(int $userType): string
    {
        return self::normalize($userType) === self::INSTRUCTOR
            ? 'instructor/dashboard'
            : 'enrollments/my-courses';
    }

    /**
     * Returns the register path for the provided user type.
     */
    public static function getRegisterPath(int $userType): string
    {
        return self::normalize($userType) === self::INSTRUCTOR
            ? 'instructor_register'
            : 'register';
    }

    /**
     * Returns an Arabic label for the user type.
     */
    public static function getLabel(int $userType): string
    {
        return self::normalize($userType) === self::INSTRUCTOR ? 'محاضر' : 'طالب';
    }
}
