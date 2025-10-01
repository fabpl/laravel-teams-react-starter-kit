<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

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

        $user->addMedia(UploadedFile::fake()->image('avatar.jpg'))->toMediaCollection('avatars');

        /** @var Team $team */
        $team = $user->currentTeam;

        TeamInvitation::factory()->count(5)->for($team)->create();

        User::factory()->count(5)->create()->each(function ($_user) use ($team): void {
            $team->users()->attach($_user->id, [
                'role' => 'collaborator',
            ]);
        });

        Team::factory()->state(['name' => 'As Collaborator'])->count(1)->create()->each(function ($_team) use ($user): void {
            $_team->users()->attach($user->id, [
                'role' => 'collaborator',
            ]);
        });

        Team::factory()->state(['name' => 'As Member'])->count(1)->create()->each(function ($_team) use ($user): void {
            $_team->users()->attach($user->id, [
                'role' => 'member',
            ]);
        });
    }
}
