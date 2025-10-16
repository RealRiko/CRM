@extends('layouts.app')

@section('title', 'Products - ' . config('app.name', 'Inventory Management'))

@section('content')
    {{-- Main Page Container --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- Card Container (Using the reusable .card component defined in app.css) --}}
        {{-- You can replace the classes below with the single .card class if you prefer 
             to use the new CSS component, but using Tailwind utilities directly is often more flexible. --}}
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700">
            
            {{-- Header Section & Create Button --}}
            <header class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">
                    Product Inventory
                </h1>
                {{-- BUTTON: NOW USING bg-sienna and hover:bg-sienna-dark --}}
                <a href="{{ route('products.create') }}" class="
                    bg-sienna hover:bg-sienna-dark text-white font-semibold 
                    py-2 px-4 rounded-xl shadow-lg 
                    transition duration-300 ease-in-out transform hover:scale-[1.02]
                    text-sm sm:text-base
                ">
                    + Create New Product
                </a>
            </header>

            {{-- Success Alert (Modernized) --}}
            @if (session('success'))
                <div class="
                    p-4 mb-6 rounded-xl border-l-4 
                    bg-green-50 dark:bg-green-900/20 
                    border-green-500 text-green-700 dark:text-green-300
                    shadow-md
                ">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Search Input (Modernized) --}}
            <div class="mb-8">
                <form method="GET" action="{{ route('products.index') }}" class="relative">
                    {{-- FOCUS RING: NOW USING focus:ring-sienna and focus:border-sienna --}}
                    <input type="text" name="search" placeholder="Search products by name or description..."
                            value="{{ request('search') }}"
                            class="
                                w-full p-3 pl-10 border border-gray-300 dark:border-gray-600 
                                rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                                focus:ring-2 focus:ring-sienna focus:border-sienna
                                transition duration-150
                            "
                            onkeyup="this.form.submit()">
                    
                    {{-- Search Icon --}}
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </form>
            </div>

            {{-- Product Table (High-Quality Design with Dark Mode) --}}
            <div class="overflow-x-auto shadow-2xl rounded-2xl">
                <table class="min-w-full 
                    bg-white dark:bg-gray-800 
                    rounded-2xl 
                    overflow-hidden
                    border-collapse
                ">
                    <thead>
                        <tr class="
                            bg-gray-50 dark:bg-gray-700 
                            border-b border-gray-200 dark:border-gray-600
                            text-xs sm:text-sm uppercase tracking-wider
                        ">
                            <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">ID</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Name</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Price</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300 hidden md:table-cell">Description</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Stock</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300 hidden sm:table-cell">Category</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-500 dark:text-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="
                                border-b border-gray-100 dark:border-gray-700 
                                {{-- HOVER COLOR: Using a standard Tailwind amber shade or the new sienna-light --}}
                                hover:bg-amber-50/50 dark:hover:bg-gray-700 
                                transition duration-150
                            ">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $product->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $product->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono">${{ number_format($product->price, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate hidden md:table-cell">{{ $product->description ?? 'No description' }}</td>
                                <td class="px-6 py-4 text-sm font-bold 
                                    @if(isset($product->stock) && $product->stock < 10) 
                                        text-red-500 dark:text-red-400
                                    @elseif(isset($product->stock) && $product->stock < 50)
                                        text-yellow-600 dark:text-yellow-400
                                    @else
                                        text-green-600 dark:text-green-400
                                    @endif
                                ">
                                    {{ $product->stock ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">{{ $product->category ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    {{-- EDIT LINK: NOW USING text-sienna and hover:text-sienna-dark --}}
                                    <a href="{{ route('products.edit', $product) }}" class="
                                        text-sienna dark:text-sienna hover:text-sienna-dark
                                        font-semibold transition duration-150
                                    ">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-lg text-gray-400 dark:text-gray-500">
                                    <svg class="inline-block w-8 h-8 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{-- Original call: {{ $products->links() ?? '' }} --}}
            </div>
            
        </div>
    </div>
@endsection