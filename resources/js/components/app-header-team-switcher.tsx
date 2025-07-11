import { Link, usePage } from '@inertiajs/react';
import { Check, ChevronsUpDown, Plus } from 'lucide-react';

import AppLogoIcon from '@/components/app-logo-icon';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { SharedData } from '@/types';

export function AppHeaderTeamSwitcher() {
    const { auth } = usePage<SharedData>().props;

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button size="lg" variant="secondary" className="w-48 data-[state=open]:bg-accent data-[state=open]:text-accent-foreground">
                    <div className="flex aspect-square size-8 items-center justify-center rounded-lg bg-secondary text-secondary-foreground">
                        <AppLogoIcon className="size-5 fill-current" />
                    </div>
                    <div className="grid flex-1 text-left text-sm leading-tight">
                        <span className="truncate font-medium">{auth.currentTeam.name}</span>
                    </div>
                    <ChevronsUpDown className="ml-auto" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent className="min-w-56 rounded-lg" align="start">
                <DropdownMenuLabel className="text-xs text-muted-foreground">Teams</DropdownMenuLabel>
                {auth.teams.map((team) => (
                    <DropdownMenuItem key={team.name} className="gap-2 p-2">
                        {team.id == auth.currentTeam.id ? (
                            <>
                                <span>{team.name}</span>
                                <Check className="ml-auto size-4" />
                            </>
                        ) : (
                            <Link className="w-full text-left" data={{ team_id: team.id }} href={route('current-team.update')} method="put">
                                {team.name}
                            </Link>
                        )}
                    </DropdownMenuItem>
                ))}
                <DropdownMenuSeparator />
                <DropdownMenuItem asChild>
                    <Link className="gap-2 p-2" href={route('teams.create')} prefetch>
                        <div className="flex size-6 items-center justify-center rounded-md border bg-transparent">
                            <Plus className="size-4" />
                        </div>
                        <div className="font-medium text-muted-foreground">Add team</div>
                    </Link>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
