<?php

use App\Enums\Role;
use App\Enums\Tier;
use App\Models\Resource;
use App\Models\User;
use Database\Seeders\PrmsSeeder;

it('seeds exactly three crew leads', function () {
    $this->seed(PrmsSeeder::class);

    expect(User::query()->where('role', Role::CrewLead)->count())->toBe(3);
});

it('seeds passengers for each membership tier', function () {
    $this->seed(PrmsSeeder::class);

    expect(User::query()->where('role', Role::Passenger)->count())->toBe(3)
        ->and(User::query()->where('tier', Tier::Silver)->count())->toBe(1)
        ->and(User::query()->where('tier', Tier::Gold)->count())->toBe(1)
        ->and(User::query()->where('tier', Tier::Platinum)->count())->toBe(1);
});

it('seeds ship resources across tier requirements', function () {
    $this->seed(PrmsSeeder::class);

    expect(Resource::query()->count())->toBe(8)
        ->and(Resource::query()->where('minimum_tier', Tier::Silver)->count())->toBe(3)
        ->and(Resource::query()->where('minimum_tier', Tier::Gold)->count())->toBe(3)
        ->and(Resource::query()->where('minimum_tier', Tier::Platinum)->count())->toBe(2);
});
