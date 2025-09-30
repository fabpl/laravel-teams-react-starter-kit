import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { update } from '@/routes/profile';
import { send } from '@/routes/verification';
import { type SharedData } from '@/types';
import { Transition } from '@headlessui/react';
import { Form, Link, usePage } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';

interface UpdateProfileFormProps {
    mustVerifyEmail: boolean;
}

export default function UpdateProfileForm({ mustVerifyEmail }: UpdateProfileFormProps) {
    const { auth } = usePage<SharedData>().props;

    return (
        <div className="space-y-6">
            <HeadingSmall title="Profile information" description="Update your name and email address" />

            <Form
                {...update.form()}
                options={{
                    preserveScroll: true,
                }}
                className="space-y-6"
            >
                {({ processing, recentlySuccessful, errors }) => (
                    <>
                        <div className="grid gap-2">
                            <Label htmlFor="name">Name</Label>

                            <Input
                                id="name"
                                name="name"
                                className="mt-1 block w-full"
                                defaultValue={auth.user.name}
                                required
                                autoComplete="username"
                                placeholder="Full name"
                            />

                            <InputError className="mt-2" message={errors.name} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="email">Email address</Label>

                            <Input
                                id="email"
                                type="email"
                                name="email"
                                className="mt-1 block w-full"
                                defaultValue={auth.user.email}
                                required
                                autoComplete="email"
                                placeholder="Email address"
                            />

                            <InputError className="mt-2" message={errors.email} />
                        </div>

                        {mustVerifyEmail && auth.user.email_verified_at === null && (
                            <div>
                                <p className="-mt-4 text-sm text-muted-foreground">
                                    Your email address is unverified.{' '}
                                    <Link
                                        href={send()}
                                        method="post"
                                        as="button"
                                        className="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                                    >
                                        Click here to resend the verification email.
                                    </Link>
                                </p>
                            </div>
                        )}

                        <div className="flex items-center gap-4">
                            <Button disabled={processing} type="submit">
                                {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                                Save
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
