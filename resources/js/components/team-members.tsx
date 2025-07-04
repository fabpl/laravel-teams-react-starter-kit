import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import InviteTeamMember from '@/components/invite-team-member';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Label } from '@/components/ui/label';
import { Pagination, PaginationContent, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type Paginated, type SharedData, type Team, type TeamMember, TeamRoles } from '@/types';
import { useForm, usePage } from '@inertiajs/react';
import { Ellipsis, LoaderCircle } from 'lucide-react';
import { useState } from 'react';

type UpdateTeamMemberForm = {
    role: string;
};

export default function TeamMembers({ team, members }: { team: Team; members: Paginated<TeamMember> }) {
    const { auth } = usePage<SharedData>().props;

    const [openUpdatingTeamMember, setOpenUpdatingTeamMember] = useState(false);
    const [updatingTeamMemberFor, setUpdatingTeamMemberFor] = useState<TeamMember | null>(null);

    const updatingTeamMember = (member: TeamMember) => {
        setUpdatingTeamMemberFor(member);
        setOpenUpdatingTeamMember(true);
        updateTeamMemberForm.setData('role', member.role);
    };

    const updateTeamMemberForm = useForm<Required<UpdateTeamMemberForm>>({
        role: '',
    });

    const updateTeamMember = () => {
        if (!updatingTeamMemberFor) {
            return;
        }

        updateTeamMemberForm.patch(route('team-members.update', { team: team, member: updatingTeamMemberFor.user_id }), {
            preserveScroll: true,
            onSuccess: () => closeTeamMemberModal(),
        });
    };

    const closeTeamMemberModal = () => {
        setUpdatingTeamMemberFor(null);
        setOpenUpdatingTeamMember(false);
    };

    const [openDeletingTeamInvitation, setOpenDeletingTeamInvitation] = useState(false);
    const [deletingTeamInvitationFor, setDeletingTeamInvitationFor] = useState<TeamMember | null>(null);

    const deletingTeamInvitation = (member: TeamMember) => {
        setDeletingTeamInvitationFor(member);
        setOpenDeletingTeamInvitation(true);
    };

    const deleteTeamInvitationForm = useForm();

    const deleteTeamInvitation = () => {
        if (!deletingTeamInvitationFor) {
            return;
        }

        deleteTeamInvitationForm.delete(route('team-invitations.destroy', { invitation: deletingTeamInvitationFor.invitation_id }), {
            preserveScroll: true,
            onSuccess: () => closeTeamInvitationModal(),
        });
    };

    const closeTeamInvitationModal = () => {
        setDeletingTeamInvitationFor(null);
        setOpenDeletingTeamInvitation(false);
    };

    const [openDeletingTeamMember, setOpenDeletingTeamMember] = useState(false);
    const [deletingTeamMemberFor, setDeletingTeamMemberFor] = useState<TeamMember | null>(null);

    const deletingTeamMember = (member: TeamMember) => {
        setDeletingTeamMemberFor(member);
        setOpenDeletingTeamMember(true);
    };

    const deleteTeamMemberForm = useForm();

    const deleteTeamMember = () => {
        if (!deletingTeamMemberFor) {
            return;
        }

        deleteTeamMemberForm.delete(
            route('team-members.destroy', {
                team: team,
                member: deletingTeamMemberFor.user_id,
            }),
            {
                preserveScroll: true,
                onSuccess: () => closeDeletingTeamMemberModal(),
            },
        );
    };

    const closeDeletingTeamMemberModal = () => {
        setDeletingTeamMemberFor(null);
        setOpenDeletingTeamMember(false);
    };

    return (
        <div className="space-y-6">
            <HeadingSmall title="Members" description="Manage invitations and members." />

            {auth.permissions.includes('team-member.create') && <InviteTeamMember team={team} />}

            {members.total > 0 && (
                <>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Email</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Role</TableHead>
                                <TableHead></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {members.data.map((member) => (
                                <TableRow key={member.email}>
                                    <TableCell>{member.email}</TableCell>
                                    <TableCell>{member.status === 'pending' && <Badge variant="outline">Pending</Badge>}</TableCell>
                                    <TableCell>{member.role}</TableCell>
                                    <TableCell className="text-right">
                                        {member.user_id !== auth.user.id && (
                                            <DropdownMenu>
                                                <DropdownMenuTrigger>
                                                    <Ellipsis className="size-4" />
                                                </DropdownMenuTrigger>
                                                <DropdownMenuContent align="end">
                                                    {auth.permissions.includes('team-member.update') && member.status === 'active' && (
                                                        <DropdownMenuItem onClick={() => updatingTeamMember(member)}>Edit</DropdownMenuItem>
                                                    )}

                                                    {auth.permissions.includes('team-member.delete') && member.status === 'pending' && (
                                                        <DropdownMenuItem onClick={() => deletingTeamInvitation(member)}>Cancel</DropdownMenuItem>
                                                    )}

                                                    {auth.permissions.includes('team-member.delete') && member.status === 'active' && (
                                                        <DropdownMenuItem onClick={() => deletingTeamMember(member)}>Remove</DropdownMenuItem>
                                                    )}
                                                </DropdownMenuContent>
                                            </DropdownMenu>
                                        )}
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>

                    <Pagination>
                        <PaginationContent>
                            {members.prev_page_url && (
                                <PaginationItem>
                                    <PaginationPrevious href={members.prev_page_url} />
                                </PaginationItem>
                            )}
                            {members.next_page_url && (
                                <PaginationItem>
                                    <PaginationNext href={members.next_page_url} />
                                </PaginationItem>
                            )}
                        </PaginationContent>
                    </Pagination>
                </>
            )}

            <Dialog open={openDeletingTeamInvitation} onOpenChange={setOpenDeletingTeamInvitation}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Remove Team Invitation</DialogTitle>
                        <DialogDescription>Are you sure you would like to cancel this invitation to the team?</DialogDescription>
                    </DialogHeader>

                    <DialogFooter>
                        <Button disabled={deleteTeamInvitationForm.processing} onClick={() => deleteTeamInvitation()} type="button">
                            {deleteTeamInvitationForm.processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                            Cancel
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            <Dialog open={openUpdatingTeamMember} onOpenChange={setOpenUpdatingTeamMember}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Team Member</DialogTitle>
                        <DialogDescription></DialogDescription>
                    </DialogHeader>

                    <div className="grid gap-6">
                        <Label className="sr-only" htmlFor="role">
                            Role
                        </Label>

                        <Select
                            onValueChange={(role) => updateTeamMemberForm.setData('role', role as TeamRoles)}
                            defaultValue={updateTeamMemberForm.data.role}
                        >
                            <SelectTrigger className="w-full">
                                <SelectValue placeholder="Select role" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="admin">Admin</SelectItem>
                                <SelectItem value="collaborator">Collaborator</SelectItem>
                            </SelectContent>
                        </Select>

                        <InputError className="mt-2" message={updateTeamMemberForm.errors.role} />
                    </div>

                    <DialogFooter>
                        <Button disabled={updateTeamMemberForm.processing} onClick={() => updateTeamMember()} type="button">
                            {updateTeamMemberForm.processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                            Save
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            <Dialog open={openDeletingTeamMember} onOpenChange={setOpenDeletingTeamMember}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Remove Team Member</DialogTitle>
                        <DialogDescription>Are you sure you would like to remove this member to the team?</DialogDescription>
                    </DialogHeader>

                    <DialogFooter>
                        <Button disabled={deleteTeamMemberForm.processing} onClick={() => deleteTeamMember()} type="button">
                            {deleteTeamMemberForm.processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                            Remove
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    );
}
