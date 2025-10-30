<?php

it('displays welcome page for guests', function () {
    $this->get('/')->assertStatus(200)->assertSee('Welcome');
});

it('redirects authenticated users to dashboard', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user)->get('/')->assertRedirect(route('dashboard'));
});