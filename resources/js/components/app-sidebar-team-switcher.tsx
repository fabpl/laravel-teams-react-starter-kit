import AppLogoIcon from '@/components/app-logo-icon';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem, useSidebar } from '@/components/ui/sidebar';
import { update } from '@/routes/current-team';
import { create, edit } from '@/routes/teams';
import type { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Check, ChevronsUpDown, Plus, Settings2 } from 'lucide-react';

export function AppSidebarTeamSwitcher() {
    const { auth } = usePage<SharedData>().props;

    const { isMobile } = useSidebar();

    return (
        <SidebarMenu>
            <SidebarMenuItem>
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <SidebarMenuButton size="lg" className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground">
                            <div className="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                                <AppLogoIcon className="size-5 fill-current text-white dark:text-black" />
                            </div>
                            <div className="grid flex-1 text-left text-sm leading-tight">
                                <span className="truncate font-medium">{auth.currentTeam.name}</span>
                            </div>
                            <ChevronsUpDown className="ml-auto" />
                        </SidebarMenuButton>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                        className="w-(--radix-dropdown-menu-trigger-width) min-w-56 rounded-lg"
                        align="start"
                        side={isMobile ? 'bottom' : 'right'}
                        sideOffset={4}
                    >
                        <DropdownMenuLabel className="text-xs text-muted-foreground">Teams</DropdownMenuLabel>
                        {auth.teams.map((team) => (
                            <DropdownMenuItem key={team.name} className="gap-2 p-2">
                                {team.id == auth.currentTeam.id ? (
                                    <>
                                        <span>{team.name}</span>
                                        <Check className="ml-auto size-4" />
                                    </>
                                ) : (
                                    <Link className="w-full text-left" data={{ team_id: team.id }} href={update()} method="put">
                                        {team.name}
                                    </Link>
                                )}
                            </DropdownMenuItem>
                        ))}
                        {(auth.permissions.includes('team.update') || auth.permissions.includes('team-member.create')) && <DropdownMenuSeparator />}
                        {auth.permissions.includes('team.update') && (
                            <DropdownMenuItem asChild>
                                <Link className="gap-2 p-2" href={edit(auth.currentTeam)} prefetch>
                                    <div className="flex size-6 items-center justify-center rounded-md border bg-transparent">
                                        <Settings2 className="size-4" />
                                    </div>
                                    <div className="font-medium text-muted-foreground">Settings</div>
                                </Link>
                            </DropdownMenuItem>
                        )}
                        {auth.permissions.includes('team-member.create') && (
                            <DropdownMenuItem asChild>
                                <Link className="gap-2 p-2" href={create()} prefetch>
                                    <div className="flex size-6 items-center justify-center rounded-md border bg-transparent">
                                        <Plus className="size-4" />
                                    </div>
                                    <div className="font-medium text-muted-foreground">Add team</div>
                                </Link>
                            </DropdownMenuItem>
                        )}
                    </DropdownMenuContent>
                </DropdownMenu>
            </SidebarMenuItem>
        </SidebarMenu>
    );
}
