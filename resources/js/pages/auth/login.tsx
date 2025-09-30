import LoginForm from '@/components/forms/login-form';
import AuthLayout from '@/layouts/auth-layout';
import { Head } from '@inertiajs/react';

interface LoginProps {
    canResetPassword: boolean;
}

export default function Login({ canResetPassword }: LoginProps) {
    return (
        <AuthLayout title="Log in to your account" description="Enter your email and password below to log in">
            <Head title="Log in" />

            <LoginForm canResetPassword={canResetPassword} />
        </AuthLayout>
    );
}
