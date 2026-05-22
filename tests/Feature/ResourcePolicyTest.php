<?php

use App\Enums\Tier;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

it('allows passengers to use resources their tier can access', function () {
    $passenger = User::factory()->passenger(Tier::Gold)->create();
    $resource = Resource::factory()->minimumTier(Tier::Silver)->create();

    expect(Gate::forUser($passenger)->allows('use', $resource))->toBeTrue();
});

it('denies passengers from using resources above their tier', function () {
    $passenger = User::factory()->passenger(Tier::Silver)->create();
    $resource = Resource::factory()->minimumTier(Tier::Gold)->create();

    expect(Gate::forUser($passenger)->denies('use', $resource))->toBeTrue();
});

it('denies use of inactive resources', function () {
    $passenger = User::factory()->passenger(Tier::Platinum)->create();
    $resource = Resource::factory()->inactive()->create();

    expect(Gate::forUser($passenger)->denies('use', $resource))->toBeTrue();
});

it('allows crew leads to manage resources', function () {
    $crew = User::factory()->crewLead()->create();
    $resource = Resource::factory()->create();

    expect(Gate::forUser($crew)->allows('manage', $resource))->toBeTrue();
});

it('denies passengers from managing resources', function () {
    $passenger = User::factory()->passenger()->create();
    $resource = Resource::factory()->create();

    expect(Gate::forUser($passenger)->denies('manage', $resource))->toBeTrue();
});
