import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';
import { LoaderCircle } from 'lucide-react';

type CreateTeamForm = {
    name: string;
};

export default function Create() {
    const { data, setData, post, errors, processing } = useForm<Required<CreateTeamForm>>({
        name: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('teams.store'));
    };

    return (
        <AuthLayout title="Create Team" description="Create a new team to collaborate with others.">
            <Head title="Create Team" />

            <form className="flex flex-col gap-6" onSubmit={submit}>
                <div className="grid gap-6">
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

                <Button disabled={processing} type="submit" className="mt-4 w-full">
                    {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                    Create
                </Button>
            </form>
        </AuthLayout>
    );
}
