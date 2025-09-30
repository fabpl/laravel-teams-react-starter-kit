<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TeamRoles;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property TeamRoles $role
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMembership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMembership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMembership query()
 * @mixin \Eloquent
 */
final class TeamMembership extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'team_id' => 'integer',
            'user_id' => 'integer',
            'role' => TeamRoles::class,
        ];
    }
}
