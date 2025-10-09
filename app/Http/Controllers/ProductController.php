<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Require authentication for all actions.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of products for the authenticated user's company.
     */
    public function index(Request $request)
    {
        Log::info('ProductController@index accessed by user ID: ' . auth()->id());

        $company = auth()->user()->company;
        if (!$company) {
            Log::warning('User ID ' . auth()->id() . ' attempted to access products without a company.');
            return redirect()->route('dashboard')->with('error', 'No company assigned.');
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

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $company = auth()->user()->company;

        if (!$company || !auth()->user()->isAdmin()) {
            Log::warning('Unauthorized product creation attempt by user ID ' . auth()->id());
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        return view('products.create', compact('company'));
    }

    /**
     * Store a newly created product in the database.
     */
    public function store(Request $request)
    {
        Log::info('ProductController@store called by user ID: ' . auth()->id(), $request->all());

        $company = auth()->user()->company;

        if (!$company || !auth()->user()->isAdmin()) {
            Log::warning('Unauthorized attempt to store a product by user ID ' . auth()->id());
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'stock'       => ['nullable', 'integer', 'min:0'],
            'category'    => ['nullable', 'string', 'max:255'],
        ]);

        $validated['stock'] = $validated['stock'] ?? 0;
        $validated['company_id'] = $company->id;

        $product = Product::create($validated);

        Log::info('Product created successfully', [
            'product_id' => $product->id,
            'company_id' => $company->id,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing an existing product.
     */
    public function edit(Product $product)
    {
        $company = auth()->user()->company;

        if (!$company || $product->company_id !== $company->id || !auth()->user()->isAdmin()) {
            Log::warning("Unauthorized access to edit product ID {$product->id} by user ID " . auth()->id());
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product in the database.
     */
    public function update(Request $request, Product $product)
    {
        Log::info('ProductController@update called', [
            'product_id' => $product->id,
            'user_id'    => auth()->id(),
            'data'       => $request->all(),
        ]);

        $company = auth()->user()->company;

        if (!$company || $product->company_id !== $company->id || !auth()->user()->isAdmin()) {
            Log::warning("Unauthorized attempt to update product ID {$product->id} by user ID " . auth()->id());
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'stock'       => ['nullable', 'integer', 'min:0'],
            'category'    => ['nullable', 'string', 'max:255'],
        ]);

        $validated['stock'] = $validated['stock'] ?? 0;

        $product->update($validated);

        Log::info('Product updated successfully', [
            'product_id' => $product->id,
            'company_id' => $company->id,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }
}

1