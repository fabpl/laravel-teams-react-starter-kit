import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { request } from '@/routes/password';
import { Form } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';

export default function ForgotPasswordForm() {
    return (
        <Form {...request.form()}>
            {({ processing, errors }) => (
                <>
                    <div className="grid gap-2">
                        <Label htmlFor="email">Email address</Label>
                        <Input id="email" type="email" name="email" autoComplete="off" autoFocus placeholder="email@example.com" />

                        <InputError message={errors.email} />
                    </div>

                    <div className="my-6 flex items-center justify-start">
                        <Button className="w-full" disabled={processing}>
                            {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                            Email password reset link
                        </Button>
                    </div>
                </>
            )}
        </Form>
    );
}
