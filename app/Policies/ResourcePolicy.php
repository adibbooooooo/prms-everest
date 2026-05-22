<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;

class ResourcePolicy
{
    public function use(User $user, Resource $resource): bool
    {
        if (! $user->isPassenger() || $user->tier === null) {
            return false;
        }

        if (! $resource->is_active) {
            return false;
        }

        return $user->tier->canAccess($resource->minimum_tier);
    }

    public function manage(User $user, Resource $resource): bool
    {
        return $user->isCrewLead();
    }
}
