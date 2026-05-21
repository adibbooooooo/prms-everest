<?php

use App\Enums\Role;
use App\Enums\Tier;
use App\Models\User;

it('persists passenger role and tier', function () {
    $user = User::factory()->passenger(Tier::Gold)->create();

    $user->refresh();

    expect($user->role)->toBe(Role::Passenger)
        ->and($user->tier)->toBe(Tier::Gold);
});

it('persists crew lead role without tier', function () {
    $user = User::factory()->crewLead()->create();

    $user->refresh();

    expect($user->role)->toBe(Role::CrewLead)
        ->and($user->tier)->toBeNull();
});

it('identifies crew lead and passenger helpers', function () {
    $crew = User::factory()->crewLead()->create();
    $passenger = User::factory()->passenger()->create();

    expect($crew->isCrewLead())->toBeTrue()
        ->and($crew->isPassenger())->toBeFalse()
        ->and($passenger->isPassenger())->toBeTrue()
        ->and($passenger->isCrewLead())->toBeFalse();
});
