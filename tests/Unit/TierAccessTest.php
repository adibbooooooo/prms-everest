<?php

use App\Enums\Tier;

it('allows same tier access', function () {
    expect(Tier::Silver->canAccess(Tier::Silver))->toBeTrue();
});

it('allows gold to access silver resources', function () {
    expect(Tier::Gold->canAccess(Tier::Silver))->toBeTrue();
});

it('denies silver from gold resources', function () {
    expect(Tier::Silver->canAccess(Tier::Gold))->toBeFalse();
});

it('allows platinum to access all lower tiers', function () {
    expect(Tier::Platinum->canAccess(Tier::Silver))->toBeTrue();
    expect(Tier::Platinum->canAccess(Tier::Gold))->toBeTrue();
    expect(Tier::Platinum->canAccess(Tier::Platinum))->toBeTrue();
});
