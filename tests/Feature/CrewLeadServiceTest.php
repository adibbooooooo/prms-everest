<?php

use App\Enums\Role;
use App\Enums\Tier;
use App\Exceptions\CrewLeadLimitExceeded;
use App\Exceptions\CrewLeadRosterLocked;
use App\Exceptions\CrewRosterNotReady;
use App\Models\User;
use App\Services\CrewLeadService;

describe('not more than 3 crew leads', function () {
    it('allows three crew lead accounts', function () {
        User::factory()->crewLead()->count(3)->create();

        expect(User::query()->where('role', Role::CrewLead)->count())->toBe(3);
    });

    it('blocks a fourth crew lead account', function () {
        User::factory()->crewLead()->count(3)->create();

        User::factory()->crewLead()->create();
    })->throws(CrewLeadLimitExceeded::class);
});

describe('not fewer than 3 crew leads', function () {
    it('blocks deleting a crew lead', function () {
        User::factory()->crewLead()->count(3)->create();
        $crew = User::query()->where('role', Role::CrewLead)->first();

        $crew->delete();
    })->throws(CrewLeadRosterLocked::class);
});

describe('mission ready (exactly 3 crew before operations)', function () {
    it('blocks operations when crew roster is not complete', function () {
        User::factory()->crewLead()->count(2)->create();

        app(CrewLeadService::class)->assertMissionReady();
    })->throws(CrewRosterNotReady::class);

    it('allows operations when exactly three crew leads exist', function () {
        User::factory()->crewLead()->count(3)->create();

        app(CrewLeadService::class)->assertMissionReady();

        expect(true)->toBeTrue();
    });
});

describe('crew lead profile updates', function () {
    it('lets an existing crew lead change their name', function () {
        User::factory()->crewLead()->count(3)->create();
        $crew = User::query()->where('role', Role::CrewLead)->first();

        $crew->update(['name' => 'Commander Vega']);

        expect($crew->fresh()->name)->toBe('Commander Vega');
    });
});

describe('passengers with a full crew roster', function () {
    it('still allows creating a new passenger when mission is ready', function () {
        User::factory()->crewLead()->count(3)->create();

        $passenger = User::factory()->passenger(Tier::Gold)->create();

        expect($passenger->role)->toBe(Role::Passenger)
            ->and($passenger->tier)->toBe(Tier::Gold);
    });

    it('allows deleting a passenger account', function () {
        User::factory()->crewLead()->count(3)->create();
        $passenger = User::factory()->passenger()->create();

        $passenger->delete();

        expect(User::query()->find($passenger->id))->toBeNull()
            ->and(User::query()->where('role', Role::CrewLead)->count())->toBe(3);
    });
});
