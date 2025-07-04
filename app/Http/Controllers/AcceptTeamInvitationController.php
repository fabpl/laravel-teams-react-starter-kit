<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Team\AcceptTeamInvitationAction;
use App\Models\TeamInvitation;
use Illuminate\Http\RedirectResponse;
use Throwable;

final class AcceptTeamInvitationController extends Controller
{
    /**
     * @throws Throwable
     */
    public function __invoke(TeamInvitation $invitation, AcceptTeamInvitationAction $acceptTeamInvitation): RedirectResponse
    {
        $acceptTeamInvitation->handle($invitation);

        return to_route('dashboard');
    }
}
