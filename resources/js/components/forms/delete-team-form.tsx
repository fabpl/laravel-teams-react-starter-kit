import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { destroy } from '@/routes/teams';
import { type Team } from '@/types';
import { Form } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { useRef } from 'react';

interface DeleteTeamFormProps {
    team: Team;
}

export default function DeleteTeamForm({ team }: DeleteTeamFormProps) {
    const passwordInput = useRef<HTMLInputElement>(null);

    return (
        <div className="space-y-6">
            <HeadingSmall title="Delete team" description="Delete your team and all of its resources" />
            <div className="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10">
                <div className="relative space-y-0.5 text-red-600 dark:text-red-100">
                    <p className="font-medium">Warning</p>
                    <p className="text-sm">Please proceed with caution, this cannot be undone.</p>
                </div>

                <Dialog>
                    <DialogTrigger asChild>
                        <Button variant="destructive">Delete team</Button>
                    </DialogTrigger>
                    <DialogContent>
                        <DialogTitle>Are you sure you want to delete your team?</DialogTitle>
                        <DialogDescription>
                            Once your team is deleted, all of its resources and data will also be permanently deleted. Please enter your password to
                            confirm you would like to permanently delete your team.
                        </DialogDescription>
                        <Form
                            {...destroy.form(team)}
                            options={{
                                preserveScroll: true,
                            }}
                            onError={() => passwordInput.current?.focus()}
                            resetOnSuccess
                            className="space-y-6"
                        >
                            {({ resetAndClearErrors, processing, errors }) => (
                                <>
                                    <div className="grid gap-2">
                                        <Label htmlFor="password" className="sr-only">
                                            Password
                                        </Label>

                                        <Input
                                            id="password"
                                            type="password"
                                            name="password"
                                            ref={passwordInput}
                                            placeholder="Password"
                                            autoComplete="current-password"
                                        />

                                        <InputError message={errors.password} />
                                    </div>

                                    <DialogFooter className="gap-2">
                                        <DialogClose asChild>
                                            <Button variant="secondary" onClick={() => resetAndClearErrors()}>
                                                Cancel
                                            </Button>
                                        </DialogClose>

                                        <Button variant="destructive" disabled={processing} type="submit">
                                            {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                                            Delete team
                                        </Button>
                                    </DialogFooter>
                                </>
                            )}
                        </Form>
                    </DialogContent>
                </Dialog>
            </div>
        </div>
    );
}
