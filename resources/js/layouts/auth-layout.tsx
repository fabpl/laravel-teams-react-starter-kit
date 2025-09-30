import ToasterProvider from '@/components/toaster-provider';
import { Toaster } from '@/components/ui/sonner';
import AuthLayoutTemplate from '@/layouts/auth/auth-simple-layout';
import { ReactNode } from 'react';

interface AuthLayoutProps {
    children: ReactNode;
    title: string;
    description: string;
}

export default function AuthLayout({ children, title, description, ...props }: AuthLayoutProps) {
    return (
        <ToasterProvider>
            <AuthLayoutTemplate title={title} description={description} {...props}>
                {children}
                <Toaster />
            </AuthLayoutTemplate>
        </ToasterProvider>
    );
}
