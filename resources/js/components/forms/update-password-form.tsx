import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { update } from '@/routes/password';
import { Transition } from '@headlessui/react';
import { Form } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { useRef } from 'react';

export default function UpdatePasswordForm() {
    const passwordInput = useRef<HTMLInputElement>(null);
    const currentPasswordInput = useRef<HTMLInputElement>(null);

    return (
        <div className="space-y-6">
            <HeadingSmall title="Update password" description="Ensure your account is using a long, random password to stay secure" />

            <Form
                {...update.form()}
                options={{
                    preserveScroll: true,
                }}
                resetOnError={['password', 'password_confirmation', 'current_password']}
                resetOnSuccess
                onError={(errors) => {
                    if (errors.password) {
                        passwordInput.current?.focus();
                    }

                    if (errors.current_password) {
                        currentPasswordInput.current?.focus();
                    }
                }}
                className="space-y-6"
            >
                {({ errors, processing, recentlySuccessful }) => (
                    <>
                        <div className="grid gap-2">
                            <Label htmlFor="current_password">Current password</Label>

                            <Input
                                id="current_password"
                                name="current_password"
                                ref={currentPasswordInput}
                                type="password"
                                className="mt-1 block w-full"
                                autoComplete="current-password"
                                placeholder="Current password"
                            />

                            <InputError message={errors.current_password} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="password">New password</Label>

                            <Input
                                id="password"
                                name="password"
                                ref={passwordInput}
                                type="password"
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                                placeholder="New password"
                            />

                            <InputError message={errors.password} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="password_confirmation">Confirm password</Label>

                            <Input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                                placeholder="Confirm password"
                            />

                            <InputError message={errors.password_confirmation} />
                        </div>

                        <div className="flex items-center gap-4">
                            <Button disabled={processing} type="submit">
                                {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                                Save password
                            </Button>

                            <Transition
                                show={recentlySuccessful}
                                enter="transition ease-in-out"
                                enterFrom="opacity-0"
                                leave="transition ease-in-out"
                                leaveTo="opacity-0"
                            >
                                <p className="text-sm text-neutral-600">Saved</p>
                            </Transition>
                        </div>
                    </>
                )}
            </Form>
        </div>
    );
}
