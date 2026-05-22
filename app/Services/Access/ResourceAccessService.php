<?php

namespace App\Services\Access;

use App\Exceptions\ResourceAccessDenied;
use App\Models\Resource;
use App\Models\User;

class ResourceAccessService
{
    public function ensureCanUse(User $passenger, Resource $resource): void
    {
        if (! $passenger->isPassenger() || $passenger->tier === null) {
            throw new ResourceAccessDenied('Only passengers with a membership tier may use resources.');
        }

        if (! $resource->is_active) {
            throw new ResourceAccessDenied('This resource is not available.');
        }

        if (! $passenger->tier->canAccess($resource->minimum_tier)) {
            throw new ResourceAccessDenied('Your membership tier does not allow this resource.');
        }
    }
}
