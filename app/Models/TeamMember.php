<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TeamMemberStatus;
use App\Enums\TeamRoles;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|null $invitation_id
 * @property int|null $team_id
 * @property int|null $user_id
 * @property string|null $email
 * @property TeamRoles|null $role
 * @property TeamMemberStatus|null $status
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereInvitationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereUserId($value)
 * @mixin \Eloquent
 */
final class TeamMember extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'invitation_id' => 'integer',
            'team_id' => 'integer',
            'user_id' => 'integer',
            'role' => TeamRoles::class,
            'status' => TeamMemberStatus::class,
        ];
    }
}
