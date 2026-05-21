<?php

use App\Enums\Tier;
use App\Models\Resource;

it('creates resource with minimum tier', function () {
    $resource = Resource::factory()->create([
        'name' => 'Sleeping Pod',
        'minimum_tier' => Tier::Silver,
    ]);

    expect($resource->minimum_tier)->toBe(Tier::Silver)
        ->and($resource->is_active)->toBeTrue();
});

it('can deactivate a resource', function () {
    $resource = Resource::factory()->inactive()->create();

    expect($resource->is_active)->toBeFalse();
});
