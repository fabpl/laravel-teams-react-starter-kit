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

export default function Profile({ mustVerifyEmail, status }: { mustVerifyEmail: boolean; status?: string }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Profile settings" />

            <SettingsLayout>
                <UpdateProfileForm mustVerifyEmail={mustVerifyEmail} status={status} />

                <DeleteUserForm />
            </SettingsLayout>
        </AppLayout>
    );
}
