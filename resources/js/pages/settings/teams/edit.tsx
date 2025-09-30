import DeleteTeamForm from '@/components/forms/delete-team-form';
import UpdateTeamForm from '@/components/forms/update-team-form';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { edit } from '@/routes/teams';
import { type BreadcrumbItem, type Paginated, type SharedData, type Team, type TeamMember } from '@/types';
import { Head, usePage } from '@inertiajs/react';

export default function Edit({ team }: { team: Team; members: Paginated<TeamMember> }) {
    const { auth } = usePage<SharedData>().props;

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Team settings',
            href: edit(team).url,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Team settings" />

            <SettingsLayout>
                <UpdateTeamForm team={team} />

                {auth.permissions.includes('team.delete') && <DeleteTeamForm team={team} />}
            </SettingsLayout>
        </AppLayout>
    );
}
