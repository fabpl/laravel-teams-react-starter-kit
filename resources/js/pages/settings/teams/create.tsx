import CreateTeamForm from '@/components/forms/create-team-form';
import AuthLayout from '@/layouts/auth-layout';
import { Head } from '@inertiajs/react';

export default function Create() {
    return (
        <AuthLayout title="Create Team" description="Create a new team to collaborate with others.">
            <Head title="Create Team" />

            <CreateTeamForm />
        </AuthLayout>
    );
}
