<?php

namespace App\Services;

use App\Enums\Role;
use App\Exceptions\CrewLeadLimitExceeded;
use App\Exceptions\CrewLeadRosterLocked;
use App\Exceptions\CrewRosterNotReady;
use App\Models\User;

class CrewLeadService
{
    private const CREW_LEAD_COUNT = 3;

    public function assertMissionReady(): void
    {
        if (User::query()->where('role', Role::CrewLead)->count() !== self::CREW_LEAD_COUNT) {
            throw new CrewRosterNotReady(
                'Mission systems are locked until exactly 3 crew lead accounts are active.',
            );
        }
    }

    public function validateCrewLeadRole(User $user): void
    {
        if ($user->role !== Role::CrewLead) {
            return;
        }

        if ($user->exists && $user->getOriginal('role') === Role::CrewLead) {
            return;
        }

        if (User::query()->where('role', Role::CrewLead)->count() >= self::CREW_LEAD_COUNT) {
            throw new CrewLeadLimitExceeded(
                'Exactly 3 crew leads are allowed. Cannot add a fourth crew lead account.',
            );
        }
    }

    public function validateDeletion(User $user): void
    {
        if ($user->isCrewLead()) {
            throw new CrewLeadRosterLocked(
                'Exactly 3 crew leads are required. A crew lead account cannot be deleted.',
            );
        }
    }
}
