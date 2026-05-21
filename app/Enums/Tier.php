<?php

namespace App\Enums;

enum Tier: string
{
    case Silver = 'silver';
    case Gold = 'gold';
    case Platinum = 'platinum';

    public function rank(): int
    {
        return match ($this) {
            self::Silver => 1,
            self::Gold => 2,
            self::Platinum => 3,
        };
    }

    public function canAccess(self $required): bool
    {
        return $this->rank() >= $required->rank();
    }
}
