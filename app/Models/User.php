<?php

declare(strict_types=1);

namespace App\Models;

use App\Actions\Team\UpdateCurrentTeamAction;
use App\Enums\TeamPermissions;
use App\Enums\TeamRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Throwable;

/**
 * @property int $id
 * @property int|null $current_team_id
 * @property string $name
 * @property string $email
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read Team|null $currentTeam
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read TeamMembership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Team> $teams
 * @property-read int|null $teams_count
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, InteractsWithMedia, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function belongsToTeam(Team $team): bool
    {
        return $this->teams->contains(fn (Team $t): bool => $t->id === $team->id);
    }

    /**
     * @return BelongsTo<Team, $this>
     *
     * @throws Throwable
     */
    public function currentTeam(): BelongsTo
    {
        if (blank($this->current_team_id) && filled($this->id)) {
            $team = $this->teams->first();

            if (filled($team)) {
                /** @var UpdateCurrentTeamAction $action */
                $action = app(UpdateCurrentTeamAction::class);

                $action->handle($this, $team);
            }
        }

        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function hasTeamPermission(Team $team, TeamPermissions $permission): bool
    {
        if (! $this->belongsToTeam($team)) {
            return false;
        }

        $permissions = $this->teamPermissions($team);

        return in_array($permission, $permissions);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatars')
            ->singleFile()
            ->registerMediaConversions(function (): void {
                $this
                    ->addMediaConversion('thumb')
                    ->width(64)
                    ->height(64);
            });
    }

    /**
     * @return TeamPermissions[]
     */
    public function teamPermissions(Team $team): array
    {
        if (! $this->belongsToTeam($team)) {
            return [];
        }

        /** @var TeamRoles $role */
        $role = $this->teamRole($team);

        return $role->permissions();
    }

    public function teamRole(Team $team): ?TeamRoles
    {
        if (! $this->belongsToTeam($team)) {
            return null;
        }

        /** @var User $member */
        $member = $this->teams->where('id', $team->id)->first();

        /** @var TeamMembership $membership */
        $membership = $member->membership;

        return $membership->role;
    }

    /**
     * @return BelongsToMany<Team, $this, TeamMembership, 'membership'>
     */
    public function teams(): BelongsToMany
    {
        return $this
            ->belongsToMany(Team::class, 'team_user')
            ->as('membership')
            ->using(TeamMembership::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'current_team_id' => 'integer',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
