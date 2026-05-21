<?php

namespace App\Models;

use App\Enums\Tier;
use Database\Factories\ResourceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug', 'description', 'minimum_tier', 'is_active'])]
class Resource extends Model
{
    /** @use HasFactory<ResourceFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'minimum_tier' => Tier::class,
            'is_active' => 'boolean',
        ];
    }
}
