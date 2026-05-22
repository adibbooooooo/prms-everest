<?php

use App\Enums\Tier;
use App\Models\MembershipChange;
use App\Models\User;
use App\Services\MembershipService;

it('logs tier change when crew updates passenger', function () {
    $crew = User::factory()->crewLead()->create();
    $passenger = User::factory()->passenger(Tier::Silver)->create();

    app(MembershipService::class)->changeTier($passenger, Tier::Gold, $crew);

    $passenger->refresh();

    expect($passenger->tier)->toBe(Tier::Gold)
        ->and(MembershipChange::count())->toBe(1)
        ->and(MembershipChange::first()->old_tier)->toBe(Tier::Silver)
        ->and(MembershipChange::first()->new_tier)->toBe(Tier::Gold)
        ->and(MembershipChange::first()->changed_by)->toBe($crew->id);
});
