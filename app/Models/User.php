<?php

namespace App\Models;

use App\Enums\Role;
use App\Enums\Tier;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'tier'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'tier' => Tier::class,
        ];
    }

    public function isCrewLead(): bool
    {
        return $this->role === Role::CrewLead;
    }

    public function isPassenger(): bool
    {
        return $this->role === Role::Passenger;
    }
}
