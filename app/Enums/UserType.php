<?php

namespace App\Enums;

enum UserType: string
{
    case RECRUITER = 'recruiter';
    case CANDIDATE = 'candidate';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
