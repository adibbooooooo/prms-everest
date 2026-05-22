<?php

namespace App\Services;

use App\Models\Resource;
use App\Models\ResourceUsage;
use App\Models\User;
use App\Services\Access\ResourceAccessService;

class ResourceUsageService
{
    public function __construct(
        private ResourceAccessService $access,
    ) {}

    public function recordUse(User $passenger, Resource $resource): ResourceUsage
    {
        $this->access->ensureCanUse($passenger, $resource);

        return ResourceUsage::create([
            'user_id' => $passenger->id,
            'resource_id' => $resource->id,
            'action' => 'used',
        ]);
    }
}
