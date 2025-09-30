import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { store } from '@/routes/teams';
import { Form } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';

export default function CreateTeamForm() {
    return (
        <Form {...store.form()} className="flex flex-col gap-6">
            {({ processing, errors }) => (
                <>
                    <div className="grid gap-6">
                        <Label htmlFor="name">Name</Label>

                        <Input id="name" name="name" className="mt-1 block w-full" required placeholder="Full name" />

                        <InputError className="mt-2" message={errors.name} />
                    </div>

                    <Button disabled={processing} type="submit" className="mt-4 w-full">
                        {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                        Create
                    </Button>
                </>
            )}
        </Form>
    );
}
