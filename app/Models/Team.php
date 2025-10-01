<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TeamInvitation> $invitations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TeamMember> $members
 * @property-read TeamMembership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 */
final class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    /**
     * @return HasMany<TeamInvitation, $this>
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class);
    }

    /**
     * @return HasMany<TeamMember, $this>
     */
    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * @return BelongsToMany<User, $this, TeamMembership, 'membership'>
     */
    public function users(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'team_user')
            ->as('membership')
            ->using(TeamMembership::class)
            ->withPivot('role')
            ->withTimestamps();
    }
}
