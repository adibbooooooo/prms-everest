<?php

use App\Enums\Tier;
use App\Exceptions\ResourceAccessDenied;
use App\Models\Resource;
use App\Models\User;
use App\Services\Access\ResourceAccessService;

it('denies passenger below required tier', function () {
    $passenger = User::factory()->passenger(Tier::Silver)->create();
    $resource = Resource::factory()->minimumTier(Tier::Gold)->create();

    app(ResourceAccessService::class)->ensureCanUse($passenger, $resource);
})->throws(ResourceAccessDenied::class);

it('allows passenger at required tier', function () {
    $passenger = User::factory()->passenger(Tier::Gold)->create();
    $resource = Resource::factory()->minimumTier(Tier::Silver)->create();

    app(ResourceAccessService::class)->ensureCanUse($passenger, $resource);

    expect(true)->toBeTrue();
});

it('denies use of inactive resource', function () {
    $passenger = User::factory()->passenger(Tier::Platinum)->create();
    $resource = Resource::factory()->inactive()->create();

    app(ResourceAccessService::class)->ensureCanUse($passenger, $resource);
})->throws(ResourceAccessDenied::class);

it('denies crew lead from using a resource', function () {
    $crew = User::factory()->crewLead()->create();
    $resource = Resource::factory()->create();

    app(ResourceAccessService::class)->ensureCanUse($crew, $resource);
})->throws(ResourceAccessDenied::class);
