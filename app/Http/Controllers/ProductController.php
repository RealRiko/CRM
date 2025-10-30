<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory; // Import the new Inventory model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the product definitions (not inventory).
     */
    public function index(Request $request)
    {
        $company = auth()->user()->company;

        if (!$company) {
            return redirect()->route('company.required')->with('error', 'You must create or join a company first.');
        }

        $search = $request->query('search');
        $products = Product::query()
            ->where('company_id', $company->id)
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        // Note: The index view now lists products only by definition (name, price, etc.).
        // Inventory levels are now handled by the InventoryController.
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product definition.
     */
    public function create()
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.required')->with('error', 'Please join or create a company first.');
        }

        return view('products.create', compact('company'));
    }

    /**
     * Store a newly created product definition and initialize inventory to zero.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('dashboard')->with('error', 'Please set up your company first.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);
        
        // Remove stock field logic
        // $validated['stock'] = $validated['stock'] ?? 0;
        $validated['company_id'] = $company->id;

        $product = Product::create($validated);
        
        // Initialize the new inventory record with a starting quantity of 0
        Inventory::create([
            'product_id' => $product->id,
            'company_id' => $company->id,
            'quantity' => 0,
        ]);

        Log::info('Product definition created successfully and inventory initialized', [
            'product_id' => $product->id,
            'company_id' => $company->id,
            'user_id' => $user->id,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully. You can manage its stock in the Storage section.');
    }

    /**
     * Show the form for editing the product definition.
     */
    public function edit(Product $product)
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company || $product->company_id !== $company->id) {
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product definition.
     */
    public function update(Request $request, Product $product)
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company || $product->company_id !== $company->id) {
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        // Remove stock field logic
        // $validated['stock'] = $validated['stock'] ?? 0;
        $product->update($validated);

        Log::info('Product definition updated successfully', [
            'product_id' => $product->id,
            'company_id' => $company->id,
            'user_id' => $user->id,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

// Add this destroy method to your ProductController
public function destroy(Product $product)
{
    $user = auth()->user();
    $company = $user->company;

    // Check if user has a company
    if (!$company) {
        return redirect()->route('products.index')->with('error', 'You are not assigned to a company.');
    }

    // Check if product belongs to the same company
    if ($product->company_id !== $company->id) {
        return redirect()->route('products.index')->with('error', 'You do not have permission to delete this product.');
    }

    // Simple deletion without checking document references
    // TODO: Add document line check once we know the correct model name
    
    Log::info("Product ID {$product->id} ('{$product->name}') deleted by user ID {$user->id}");

    $product->delete();

    return redirect()
        ->route('products.index')
        ->with('success', 'Product deleted successfully.');
}


}