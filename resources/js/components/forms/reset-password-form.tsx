import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { store } from '@/routes/password';
import { Form } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';

interface ResetPasswordProps {
    token: string;
    email: string;
}

export default function ResetPasswordForm({ token, email }: ResetPasswordProps) {
    return (
        <Form
            {...store.form()}
            transform={(data) => ({ ...data, token })}
            resetOnSuccess={['password', 'password_confirmation']}
            className="grid gap-6"
        >
            {({ processing, errors }) => (
                <>
                    <div className="grid gap-2">
                        <Label htmlFor="email">Email</Label>
                        <Input
                            defaultValue={email}
                            id="email"
                            type="email"
                            name="email"
                            autoComplete="email"
                            className="mt-1 block w-full"
                            readOnly
                        />
                        <InputError message={errors.email} className="mt-2" />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="password">Password</Label>
                        <Input
                            id="password"
                            type="password"
                            name="password"
                            autoComplete="new-password"
                            className="mt-1 block w-full"
                            autoFocus
                            placeholder="Password"
                        />
                        <InputError message={errors.password} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="password_confirmation">Confirm password</Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            autoComplete="new-password"
                            className="mt-1 block w-full"
                            placeholder="Confirm password"
                        />
                        <InputError message={errors.password_confirmation} className="mt-2" />
                    </div>

                    <Button type="submit" className="mt-4 w-full" disabled={processing}>
                        {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                        Reset password
                    </Button>
                </>
            )}
        </Form>
    );
}
