<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all products and their current inventory levels (storage view).
     */
    public function index()
    {
        $company = auth()->user()->company;

        if (!$company) {
            return redirect()->route('company.required')->with('error', 'You must create or join a company first.');
        }

        // Fetch all products for the company, eagerly loading the inventory record.
        // We use the Product model as the base to ensure all defined products are listed.
        $products = Product::where('company_id', $company->id)
            ->with('inventory')
            ->latest()
            ->get();

        // The view will display name, category, and inventory->quantity (defaulting to 0 if inventory is null)
        return view('inventory.index', compact('products'));
    }

    /**
     * Update the quantity for a specific product's inventory.
     */
    public function updateQuantity(Request $request, Product $product)
    {
        $user = auth()->user();
        $company = $user->company;

        // Check ownership and permission
        if (!$company || $product->company_id !== $company->id) {
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        // Find or create the inventory record for this product
        $inventory = Inventory::updateOrCreate(
            [
                'product_id' => $product->id,
                'company_id' => $company->id,
            ],
            [
                'quantity' => $validated['quantity']
            ]
        );

        Log::info('Inventory quantity updated successfully', [
            'product_id' => $product->id,
            'new_quantity' => $inventory->quantity,
            'user_id' => $user->id,
        ]);

        return redirect()->route('inventory.index')->with('success', 'Storage quantity updated successfully for ' . $product->name . '.');
    }
}