<?php

namespace App\Enums;

enum VacancyType: string
{
    case PJ = 'PJ';
    case CLT = 'CLT';
    case FREELANCER = 'FREELANCER';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
