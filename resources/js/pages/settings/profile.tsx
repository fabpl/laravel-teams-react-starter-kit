import DeleteUserForm from '@/components/forms/delete-user-form';
import UpdateProfileForm from '@/components/forms/update-profile-form';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

interface ProfileProps {
    mustVerifyEmail: boolean;
}

export default function Profile({ mustVerifyEmail }: ProfileProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Profile settings" />

            <SettingsLayout>
                <UpdateProfileForm mustVerifyEmail={mustVerifyEmail} />

                <DeleteUserForm />
            </SettingsLayout>
        </AppLayout>
    );
}
