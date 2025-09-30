import AuthLayoutTemplate from '@/layouts/auth/auth-simple-layout';
import { ReactNode } from 'react';

interface AuthLayoutProps {
    children: ReactNode;
    title: string;
    description: string;
}

export default function AuthLayout({ children, title, description, ...props }: AuthLayoutProps) {
    return (
        <AuthLayoutTemplate title={title} description={description} {...props}>
            {children}
        </AuthLayoutTemplate>
    );
}
