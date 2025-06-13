<?php

namespace App\Http\Enum;

abstract class LevelUserEnum
{
    const LEVELS = [
        'RECRUITER' => 0,
        'CANDIDATE' => 1
    ];

    public static function getLevel($level) {
        return array_values(self::LEVELS)[$level];
    }
}
