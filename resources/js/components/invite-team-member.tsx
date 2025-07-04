import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Team, TeamRoles } from '@/types';
import { useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

type InviteTeamMemberForm = {
    email: string;
    role: TeamRoles;
};

export default function InviteTeamMember({ team }: { team: Team }) {
    const { data, setData, post, errors, reset, processing } = useForm<Required<InviteTeamMemberForm>>({
        email: '',
        role: 'collaborator',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('team-invitations.store', { team: team }), {
            preserveScroll: true,
            onFinish: () => reset(),
        });
    };

    return (
        <form onSubmit={submit} className="grid gap-6 lg:grid-cols-6">
            <div className="grid flex-2 gap-2 lg:col-span-3">
                <Label className="sr-only" htmlFor="email">
                    Email
                </Label>

                <Input
                    id="email"
                    className="mt-1 block w-full"
                    type="email"
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                    required
                    placeholder="email@example.com"
                />

                <InputError className="mt-2" message={errors.email} />
            </div>

            <div className="grid flex-1 gap-2 lg:col-span-2">
                <Label className="sr-only" htmlFor="role">
                    Role
                </Label>

                <Select onValueChange={(role) => setData('role', role as TeamRoles)} defaultValue={data.role}>
                    <SelectTrigger className="w-full">
                        <SelectValue placeholder="Select role" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="admin">Admin</SelectItem>
                        <SelectItem value="collaborator">Collaborator</SelectItem>
                    </SelectContent>
                </Select>

                <InputError className="mt-2" message={errors.role} />
            </div>

            <div className="lg:col-span-1">
                <Button disabled={processing}>
                    {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                    Invite
                </Button>
            </div>
        </form>
    );
}
