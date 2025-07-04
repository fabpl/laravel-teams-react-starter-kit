import { type BreadcrumbItem, type Paginated, type SharedData, type Team, type TeamMember } from '@/types';
import { Transition } from '@headlessui/react';
import { Head, useForm, usePage } from '@inertiajs/react';
import { FormEventHandler } from 'react';

import DeleteTeam from '@/components/delete-team';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import TeamMembers from '@/components/team-members';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { LoaderCircle } from 'lucide-react';

type EditTeamForm = {
    name: string;
};

export default function Edit({ team, members }: { team: Team; members: Paginated<TeamMember> }) {
    const { auth } = usePage<SharedData>().props;

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Team settings',
            href: '/settings/teams/' + team.id + '/edit',
        },
    ];

    const { data, setData, patch, errors, processing, recentlySuccessful } = useForm<Required<EditTeamForm>>({
        name: team.name,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        patch(route('teams.update', { team: team }), {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Team settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Team information" description="Update your team's name." />

                    <form onSubmit={submit} className="space-y-6">
                        <div className="grid gap-2">
                            <Label htmlFor="name">Name</Label>

                            <Input
                                id="name"
                                className="mt-1 block w-full"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                required
                                placeholder="Full name"
                            />

                            <InputError className="mt-2" message={errors.name} />
                        </div>

                        <div className="flex items-center gap-4">
                            <Button disabled={processing}>
                                {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                                Save
                            </Button>

                            <Transition
                                show={recentlySuccessful}
                                enter="transition ease-in-out"
                                enterFrom="opacity-0"
                                leave="transition ease-in-out"
                                leaveTo="opacity-0"
                            >
                                <p className="text-sm text-neutral-600">Saved</p>
                            </Transition>
                        </div>
                    </form>
                </div>

                <TeamMembers team={team} members={members} />

                {auth.permissions.includes('team.delete') && <DeleteTeam team={team} />}
            </SettingsLayout>
        </AppLayout>
    );
}
