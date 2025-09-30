import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { update } from '@/routes/teams';
import { type Team } from '@/types';
import { Transition } from '@headlessui/react';
import { Form } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';

interface UpdateTeamFormProps {
    team: Team;
}

export default function UpdateTeamForm({ team }: UpdateTeamFormProps) {
    return (
        <div className="space-y-6">
            <HeadingSmall title="Team information" description="Update your team's name." />

            <Form {...update.form(team)} className="space-y-6">
                {({ processing, recentlySuccessful, errors }) => (
                    <>
                        <div className="grid gap-2">
                            <Label htmlFor="name">Name</Label>

                            <Input id="name" name="name" className="mt-1 block w-full" defaultValue={team.name} required placeholder="Full name" />

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
                    </>
                )}
            </Form>
        </div>
    );
}
