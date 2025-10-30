<?php

use App\Models\User;
use function Pest\Laravel\{actingAs, get, patch, delete};

it('displays the profile page', function () {
    $user = User::factory()->create();

    actingAs($user)->get('/profile')->assertOk();
});

it('updates profile information', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    expect($user->refresh())
        ->name->toBe('Test User')
        ->email->toBe('test@example.com')
        ->email_verified_at->toBeNull();
});

it('keeps email verified if email unchanged', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($user)
        ->patch('/profile', [
            'name' => 'Updated Name',
            'email' => $user->email,
        ])
        ->assertRedirect('/profile');

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

it('deletes user account with correct password', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->delete('/profile', ['password' => 'password'])
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

it('prevents account deletion with wrong password', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->from('/profile')
        ->delete('/profile', ['password' => 'wrong-password'])
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    expect($user->fresh())->not->toBeNull();
});