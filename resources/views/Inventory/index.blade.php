@extends('layouts.app')

@section('title', 'Storage Management - ' . config('app.name', 'Inventory Management'))

@section('content')
    {{-- Custom Amber Color (Sienna Amber: #CA8A04) --}}
    <style>
        .text-amber-sienna { color: #CA8A04; }
        .border-amber-sienna { border-color: #CA8A04; }
        .bg-amber-sienna { background-color: #CA8A04; }
        .hover\:bg-amber-sienna-dark:hover { background-color: #A16207; /* A slightly darker shade for hover */ }
        .focus\:ring-amber-sienna:focus { --tw-ring-color: #CA8A04; }
        .focus\:border-amber-sienna:focus { border-color: #CA8A04; }
    </style>

    {{-- Main Page Container --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- Card Container (Dark Mode Ready) --}}
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700">
            
            {{-- Header and Search --}}
            <header class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">
                    Storage Inventory
                </h1>
            </header>

            {{-- Success Alert --}}
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

            {{-- Error Alert for Inventory Update --}}
            @if ($errors->any())
                <div class="
                    p-4 mb-6 rounded-xl border-l-4 
                    bg-red-50 dark:bg-red-900/20 
                    border-red-500 text-red-700 dark:text-red-300
                    shadow-md
                ">
                    <p class="font-bold mb-1">Stock update failed!</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Search Input --}}
            <form method="get" action="{{ route('inventory.index') }}" class="mb-6 relative">
                <input type="text" name="search" id="search" placeholder="Search by product name or category..."
                    class="
                        w-full p-3 pl-10 border border-gray-300 dark:border-gray-600 
                        rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                        focus:ring-2 focus:ring-amber-sienna focus:border-amber-sienna transition duration-150
                    "
                    value="{{ request('search') }}" onchange="this.form.submit()">
                {{-- Search Icon --}}
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>

            @if ($products->isEmpty())
                <p class="text-lg text-center text-gray-500 dark:text-gray-400 py-10">No products found for inventory management.</p>
            @else
                <div class="overflow-x-auto shadow-xl rounded-xl">
                    <table class="min-w-full bg-white dark:bg-gray-800 border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr class="text-xs sm:text-sm uppercase tracking-wider">
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300 rounded-tl-xl">Product Name</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300 hidden sm:table-cell">Category</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Price (€)</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-500 dark:text-gray-300">Current Stock</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-500 dark:text-gray-300 rounded-tr-xl">Update Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($products as $product)
                                <tr class="hover:bg-amber-50/50 dark:hover:bg-gray-700 transition duration-200 text-gray-900 dark:text-gray-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">{{ $product->category ?? 'General' }}</td>
                                    
                                    {{-- Price in EUR --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                        €{{ number_format($product->price, 2, ',', '.') }}
                                    </td>
                                    
                                    {{-- Current Stock Display with Color Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        @php
                                            $quantity = $product->inventory->quantity ?? 0;
                                            $colorClass = $quantity < 0 
                                                ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 ring-red-500'
                                                : ($quantity < 10 
                                                    ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 ring-yellow-500'
                                                    : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 ring-green-500');
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full font-bold text-sm sm:text-base ring-1 ring-inset {{ $colorClass }}">
                                            {{ $quantity }}
                                        </span>
                                    </td>
                                    
                                    {{-- Update Stock Form --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <form method="POST" action="{{ route('inventory.updateQuantity', $product) }}" class="flex items-center justify-center space-x-2">
                                            @csrf
                                            @method('PUT')
                                            
                                            <input type="number" name="quantity" placeholder="New Stock"
                                                value="{{ $product->inventory->quantity ?? 0 }}"
                                                class="
                                                    w-28 p-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                                    shadow-inner text-center dark:bg-gray-700 dark:text-gray-100
                                                    focus:ring-2 focus:ring-amber-sienna focus:border-amber-sienna transition duration-150
                                                ">

                                            <button type="submit" class="
                                                bg-amber-sienna hover:bg-amber-sienna-dark text-white font-bold 
                                                py-2.5 px-4 rounded-xl shadow-lg transition duration-300 ease-in-out text-sm
                                                transform hover:scale-[1.02] active:scale-95 whitespace-nowrap
                                            ">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
