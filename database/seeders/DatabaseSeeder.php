<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->withTeam()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        /** @var \App\Models\Team $team */
        $team = $user->currentTeam;

        TeamInvitation::factory()->count(5)->for($team)->create();

        User::factory()->count(5)->create()->each(function ($user) use ($team): void {
            $team->users()->attach($user->id, [
                'role' => 'collaborator',
            ]);
        });
    }
}
