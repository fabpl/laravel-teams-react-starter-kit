import VerifyEmailForm from '@/components/forms/verify-email-form';
import AuthLayout from '@/layouts/auth-layout';
import { Head } from '@inertiajs/react';

export default function VerifyEmail() {
    return (
        <AuthLayout title="Verify email" description="Please verify your email address by clicking on the link we just emailed to you.">
            <Head title="Email verification" />

            <VerifyEmailForm />
        </AuthLayout>
    );
}
