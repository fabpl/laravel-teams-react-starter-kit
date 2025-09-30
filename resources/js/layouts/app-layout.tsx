import ToasterProvider from '@/components/toaster-provider';
import { Toaster } from '@/components/ui/sonner';
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import { type BreadcrumbItem } from '@/types';
import { type ReactNode } from 'react';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default function AppLayout({ children, breadcrumbs, ...props }: AppLayoutProps) {
    return (
        <ToasterProvider>
            <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
                {children}
                <Toaster />
            </AppLayoutTemplate>
        </ToasterProvider>
    );
}
