<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('allows user to export financial report as PDF', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/documents/1/pdf')
        ->assertStatus(200)
        ->assertHeader('Content-Type', 'application/pdf');
});

it('returns error for invalid report period', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/documents/1/pdf?period=invalid')
        ->assertSessionHas('error');
});