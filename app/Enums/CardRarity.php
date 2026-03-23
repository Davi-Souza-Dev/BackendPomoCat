<?php

namespace App\Enums;

enum CardRarity: string
{
    case COMMON = 'common';
    case UNCOMMON = 'uncommon';
    case RARE = 'rare';
    case EPIC = 'epic';
    case LEGENDARY = 'legendary';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
