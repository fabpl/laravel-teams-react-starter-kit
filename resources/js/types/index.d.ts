import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
    currentTeam: Team;
    permissions: TeamPermissions[];
    teams: Team[];
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface Paginated<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from?: number;
    last_page: number;
    last_page_url: string;
    links: PaginatedLink[];
    next_page_url?: string;
    path: string;
    per_page: number;
    prev_page_url?: string;
    to?: number;
    total: number;
}

export interface PaginatedLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface Team {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface TeamMember {
    invitation_id: number | null;
    user_id: number | null;
    email: string;
    role: TeamRoles;
    status: TeamMemberStatus;
    created_at: string;
    updated_at: string;
}

export type TeamMemberStatus = 'pending' | 'active';

export type TeamPermissions = 'team.update' | 'team.delete' | 'team-member.create' | 'team-member.update' | 'team-member.delete';

export type TeamRoles = 'admin' | 'collaborator';

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}
