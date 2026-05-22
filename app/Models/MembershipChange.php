<?php

namespace App\Models;

use App\Enums\Tier;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'old_tier', 'new_tier', 'changed_by'])]
class MembershipChange extends Model
{
    protected function casts(): array
    {
        return [
            'old_tier' => Tier::class,
            'new_tier' => Tier::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
