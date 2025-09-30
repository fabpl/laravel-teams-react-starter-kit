import { Toaster } from '@/components/ui/sonner';
import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { ReactNode, useEffect } from 'react';
import { toast } from 'sonner';

interface BaseLayoutProps {
    children: ReactNode;
}

export default function ToasterProvider({ children }: BaseLayoutProps) {
    const page = usePage<SharedData>();
    const { flash } = page.props;

    useEffect(() => {
        if (flash?.title) {
            const variants = {
                success: toast.success,
                error: toast.error,
                warning: toast.warning,
                info: toast.info,
                default: toast.message,
            };
            const showToast = variants[flash.variant ?? 'default'] ?? toast.message;

            showToast(flash.title, { description: flash.description });
        }
    }, [flash]);

    return (
        <>
            {children}
            <Toaster />
        </>
    );
}
