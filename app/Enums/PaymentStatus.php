<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case COMPLETED = 'completed';
    case PENDING = 'pending';

    public static function all(): array
    {
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[] = $case->value;
        }

        return $cases;
    }
}
