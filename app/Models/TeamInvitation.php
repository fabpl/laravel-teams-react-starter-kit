<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TeamRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;

/**
 * @property int $id
 * @property int $team_id
 * @property string $email
 * @property TeamRoles $role
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read string $accept_url
 * @property-read Team $team
 */
final class TeamInvitation extends Model
{
    /** @use HasFactory<\Database\Factories\TeamInvitationFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return Attribute<string, never>
     */
    public function acceptUrl(): Attribute
    {
        return Attribute::get(fn (): string => URL::signedRoute('team-invitations.accept', [
            'invitation' => $this,
        ]));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'team_id' => 'integer',
            'role' => TeamRoles::class,
        ];
    }
}
