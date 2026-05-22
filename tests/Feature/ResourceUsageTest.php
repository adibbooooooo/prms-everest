<?php

use App\Enums\Tier;
use App\Exceptions\ResourceAccessDenied;
use App\Models\Resource;
use App\Models\ResourceUsage;
use App\Models\User;
use App\Services\ResourceUsageService;

it('records usage when access is allowed', function () {
    $passenger = User::factory()->passenger()->create();
    $resource = Resource::factory()->create();

    app(ResourceUsageService::class)->recordUse($passenger, $resource);

    expect(ResourceUsage::query()->count())->toBe(1)
        ->and(ResourceUsage::first()->user_id)->toBe($passenger->id)
        ->and(ResourceUsage::first()->resource_id)->toBe($resource->id)
        ->and(ResourceUsage::first()->action)->toBe('used');
});

it('does not record usage when access is denied', function () {
    $passenger = User::factory()->passenger(Tier::Silver)->create();
    $resource = Resource::factory()->minimumTier(Tier::Platinum)->create();

    app(ResourceUsageService::class)->recordUse($passenger, $resource);
})->throws(ResourceAccessDenied::class);
