<?php

namespace App\Enums;

enum OrderStatus: string
{
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
    case PROCESS = 'process';
    case WAITING = 'waiting';
    case PAYMENT = 'payment';

    public static function all(): array
    {
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[] = $case->value;
        }

        return $cases;
    }

    public static function ended(): array
    {
        return [self::CANCELLED->value, self::COMPLETED->value];
    }
}
