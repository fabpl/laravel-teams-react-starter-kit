<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('profile page is displayed', function () {
    $user = User::factory()->withTeam()->create();

    $response = $this
        ->actingAs($user)
        ->get('/settings/profile');

    $response->assertOk();
});

test('guests are redirected to the login page', function () {
    $this->get('/settings/profile')->assertRedirect('/login');
});

test('profile information can be updated', function () {
    $user = User::factory()->withTeam()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/settings/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/profile');

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('users can not update profile information with invalid field', function (string $field) {
    $user = User::factory()->withTeam()->create();

    $payload = [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ];

    $payload[$field] = '';

    $response = $this
        ->actingAs($user)
        ->patch('/settings/profile', $payload);

    $response->assertSessionHasErrors([$field]);
})->with(['name', 'email']);

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->withTeam()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/settings/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/profile');

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->withTeam()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/settings/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->withTeam()->create();

    $response = $this
        ->actingAs($user)
        ->from('/settings/profile')
        ->delete('/settings/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect('/settings/profile');

    expect($user->fresh())->not->toBeNull();
});

test('user can upload avatar', function () {
    Storage::fake('public');

    $user = User::factory()->withTeam()->create();

    $avatar = UploadedFile::fake()->image('avatar.jpg');

    $response = $this
        ->actingAs($user)
        ->patch('/settings/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'avatar' => $avatar,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/profile');

    $user->refresh();

    $media = $user->getFirstMedia('avatars');

    expect($media)->not->toBeNull();
    expect($media->mime_type)->toContain('image');
    $relativePath = $media->getPathRelativeToRoot() ?? $media->getAttribute('file_name') ?? basename($media->getPath());
    Storage::disk('public')->assertExists($relativePath);
});

test('avatar upload fails with invalid file type', function () {
    Storage::fake('public');

    $user = User::factory()->withTeam()->create();

    $file = UploadedFile::fake()->create('avatar.txt', 10);

    $response = $this
        ->actingAs($user)
        ->patch('/settings/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'avatar' => $file,
        ]);

    $response->assertSessionHasErrors(['avatar']);
});
