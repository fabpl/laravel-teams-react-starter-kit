import { cn } from '@/lib/utils';
import { type HTMLAttributes } from 'react';

interface InputErrorProps {
    message?: string;
}

export default function InputError({ message, className = '', ...props }: HTMLAttributes<HTMLParagraphElement> & InputErrorProps) {
    return message ? (
        <p {...props} className={cn('text-sm text-red-600 dark:text-red-400', className)}>
            {message}
        </p>
    ) : null;
}
