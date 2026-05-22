<?php

use App\Enums\Role;
use App\Models\User;

describe('with a mission-ready crew roster', function () {
    beforeEach(function () {
        User::factory()->crewLead()->count(3)->create();
    });

    it('allows crew leads on crew routes', function () {
        $crew = User::query()->where('role', Role::CrewLead)->first();

        $this->actingAs($crew)
            ->get(route('crew.ping'))
            ->assertOk()
            ->assertJson(['area' => 'crew']);
    });

    it('denies passengers on crew routes', function () {
        $passenger = User::factory()->passenger()->create();

        $this->actingAs($passenger)
            ->get(route('crew.ping'))
            ->assertForbidden();
    });

    it('allows passengers on passenger routes', function () {
        $passenger = User::factory()->passenger()->create();

        $this->actingAs($passenger)
            ->get(route('passenger.ping'))
            ->assertOk()
            ->assertJson(['area' => 'passenger']);
    });

    it('denies crew leads on passenger routes', function () {
        $crew = User::query()->where('role', Role::CrewLead)->first();

        $this->actingAs($crew)
            ->get(route('passenger.ping'))
            ->assertForbidden();
    });
});

it('locks PRMS routes when crew roster is incomplete', function () {
    User::factory()->crewLead()->count(2)->create();

    $passenger = User::factory()->passenger()->create();

    $this->actingAs($passenger)
        ->get(route('passenger.ping'))
        ->assertForbidden();
});
