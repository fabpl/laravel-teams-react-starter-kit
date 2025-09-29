import { store } from '@/actions/App/Http/Controllers/Settings/TeamInvitationController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Team } from '@/types';
import { Form } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';

export default function InviteTeamMember({ team }: { team: Team }) {
    return (
        <Form
            {...store.form(team)}
            options={{
                preserveScroll: true,
            }}
            resetOnSuccess
            className="grid gap-6 lg:grid-cols-6"
        >
            {({ processing, errors }) => (
                <>
                    <div className="grid flex-2 gap-2 lg:col-span-3">
                        <Label className="sr-only" htmlFor="email">
                            Email
                        </Label>

                        <Input id="email" name="email" className="mt-1 block w-full" type="email" required placeholder="email@example.com" />

                        <InputError className="mt-2" message={errors.email} />
                    </div>

                    <div className="grid flex-1 gap-2 lg:col-span-2">
                        <Label className="sr-only" htmlFor="role">
                            Role
                        </Label>

                        <Select name="role" defaultValue="collaborator">
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
                </>
            )}
        </Form>
    );
}
