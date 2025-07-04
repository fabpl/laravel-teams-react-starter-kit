<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE VIEW team_members AS
            SELECT team_invitations.id as invitation_id, team_invitations.team_id, null as user_id, team_invitations.email, team_invitations.role, 'pending' as status, team_invitations.created_at, team_invitations.updated_at FROM team_invitations
            UNION ALL
            SELECT null as invitation_id, team_user.team_id, users.id, users.email, team_user.role, 'active' as status, team_user.created_at, team_user.updated_at FROM team_user INNER JOIN users ON team_user.user_id = users.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW team_members');
    }
};
