import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { logout } from '@/routes';
import { send } from '@/routes/verification';
import { Form } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';

export default function VerifyEmailForm() {
    return (
        <Form {...send.form()} className="space-y-6 text-center">
            {({ processing }) => (
                <>
                    <Button disabled={processing} variant="secondary">
                        {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                        Resend verification email
                    </Button>

                    <TextLink href={logout()} method="post" className="mx-auto block text-sm">
                        Log out
                    </TextLink>
                </>
            )}
        </Form>
    );
}
