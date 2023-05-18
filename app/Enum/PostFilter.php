<?php

namespace App\Enum;

enum PostFilter: string
{
    case All = 'all';
    case AuthUser = 'user';

    public static function caseValues(): array
    {
        return [
            self::All->value,
            self::AuthUser->value,
        ];
    }
}
