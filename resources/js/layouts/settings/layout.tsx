import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import { edit } from '@/routes/teams';
import { type NavGroup, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Palette, RectangleEllipsis, User, Users } from 'lucide-react';
import { type PropsWithChildren } from 'react';

export default function SettingsLayout({ children }: PropsWithChildren) {
    const { auth } = usePage<SharedData>().props;

    const sidebarNavGroups: NavGroup[] = [
        {
            title: 'Account',
            items: [
                {
                    title: 'Profile',
                    href: '/settings/profile',
                    icon: User,
                },
                {
                    title: 'Password',
                    href: '/settings/password',
                    icon: RectangleEllipsis,
                },
                {
                    title: 'Appearance',
                    href: '/settings/appearance',
                    icon: Palette,
                },
            ],
        },
    ];

    if (auth.permissions.includes('team.update')) {
        sidebarNavGroups.push({
            title: 'Team',
            items: [
                {
                    title: 'Settings',
                    href: edit(auth.currentTeam).url,
                    icon: Users,
                },
            ],
        });
    }

    const currentPath = window.location.pathname;

    // When server-side rendering, we only render the layout on the client...
    if (typeof window === 'undefined') {
        return null;
    }

    return (
        <div className="px-4 py-6">
            <Heading title="Settings" description="Manage your profile and account settings" />

            <div className="flex flex-col space-y-8 lg:flex-row lg:space-y-0 lg:space-x-12">
                <aside className="flex w-full max-w-xl flex-col space-y-6 lg:w-48">
                    {sidebarNavGroups.map((group, index) => (
                        <div key={`${group.title}-${index}`}>
                            <h6 className="flex h-6 shrink-0 px-2 text-xs font-medium text-muted-foreground">{group.title}</h6>
                            <nav className="flex flex-col space-y-1 space-x-0">
                                {group.items.map((item, index) => (
                                    <Button
                                        key={`${item.href}-${index}`}
                                        size="sm"
                                        variant="ghost"
                                        asChild
                                        className={cn('w-full justify-start', {
                                            'bg-muted': currentPath === item.href,
                                        })}
                                    >
                                        <Link href={item.href} prefetch>
                                            {item.icon && <item.icon className="mr-2 h-4 w-4" />}
                                            {item.title}
                                        </Link>
                                    </Button>
                                ))}
                            </nav>
                        </div>
                    ))}
                </aside>

                <Separator className="my-6 md:hidden" />

                <div className="flex-1 md:max-w-2xl">
                    <section className="max-w-xl space-y-12">{children}</section>
                </div>
            </div>
        </div>
    );
}
