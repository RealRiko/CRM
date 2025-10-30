<?php

use App\Models\User;
use function Pest\Laravel\{actingAs, postJson};

it('synchronizes changes successfully', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->postJson(route('realtime.update'), [
            'record_type' => 'invoice',
            'record_id' => 1,
            'changes' => ['total' => 100],
        ])
        ->assertJson(['status' => 'success']);
});

it('fails to sync when offline (simulated)', function () {
    $user = User::factory()->create();

    // Simulējam tīkla kļūdu vai servera atbildi
    actingAs($user)
        ->postJson(route('realtime.update'), [
            'record_type' => 'invoice',
            'record_id' => 1,
            'changes' => ['total' => 100],
        ])
        ->assertJson([
            'status' => 'error',
            'message' => 'Unable to sync changes.'
        ]);
});
