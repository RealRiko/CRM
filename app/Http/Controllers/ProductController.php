<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.required')->with('error', 'Please join or create a company first.');
        }

        // Everyone in the company can create products now
        return view('products.create', compact('company'));
    }

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
            'stock' => ['nullable', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['stock'] = $validated['stock'] ?? 0;
        $validated['company_id'] = $company->id;

        $product = Product::create($validated);

        Log::info('Product created successfully', [
            'product_id' => $product->id,
            'company_id' => $company->id,
            'user_id' => $user->id,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company || $product->company_id !== $company->id) {
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        return view('products.edit', compact('product'));
    }

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
            'stock' => ['nullable', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['stock'] = $validated['stock'] ?? 0;
        $product->update($validated);

        Log::info('Product updated successfully', [
            'product_id' => $product->id,
            'company_id' => $company->id,
            'user_id' => $user->id,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }
}


