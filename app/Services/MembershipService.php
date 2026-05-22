<?php

namespace App\Services;

use App\Enums\Tier;
use App\Models\MembershipChange;
use App\Models\User;

class MembershipService
{
    public function changeTier(User $passenger, Tier $newTier, User $changedBy): void
    {
        $oldTier = $passenger->tier;

        $passenger->update(['tier' => $newTier]);

        MembershipChange::create([
            'user_id' => $passenger->id,
            'old_tier' => $oldTier,
            'new_tier' => $newTier,
            'changed_by' => $changedBy->id,
        ]);
    }
}
