<?php

use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('allows logged-in user to create an invoice', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'user@example.com',
        'password' => Hash::make('password'),
    ]);

    $client = Client::factory()->create();
    $product = Product::factory()->create(['price' => 50]);

    $response = $this->actingAs($user)
        ->post('/invoices', [
            'client_id' => $client->id,
            'invoice_date' => now()->format('Y-m-d'),
            'delivery_days' => 7,
            'status' => 'draft',
            'line_items' => [
                ['product_id' => $product->id, 'quantity' => 2, 'price' => 50],
            ],
        ]);

    $response->assertRedirect('/invoices');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('documents', [
        'client_id' => $client->id,
        'status' => 'draft',
    ]);
});