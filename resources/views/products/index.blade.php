@extends('layouts.app')

@section('title', 'Products - ' . config('app.name', 'Inventory Management'))

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700">
        <!-- Header -->
        <header class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">Product Inventory</h1>
            <a href="{{ route('products.create') }}" class="bg-sienna hover:bg-sienna-dark text-white font-semibold py-2 px-4 rounded-xl shadow-lg transition duration-300 ease-in-out transform hover:scale-[1.02] text-sm sm:text-base">
                + Create New Product
            </a>
        </header>

        <!-- Success Message -->
        @if (session('success'))
            <div class="p-4 mb-6 rounded-xl border-l-4 bg-green-50 dark:bg-green-900/20 border-green-500 text-green-700 dark:text-green-300 shadow-md">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 mb-6 rounded-xl border-l-4 bg-red-50 dark:bg-red-900/20 border-red-500 text-red-700 dark:text-red-300 shadow-md">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Search -->
        <div class="mb-8">
            <form method="GET" action="{{ route('products.index') }}" class="relative">
                <input type="text" name="search" placeholder="Search products by name or description..."
                       value="{{ request('search') }}"
                       class="w-full p-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-sienna focus:border-sienna transition duration-150"
                       onkeyup="this.form.submit()">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>

        <!-- Products Table -->
        <div class="overflow-x-auto shadow-2xl rounded-2xl">
            <table class="min-w-full bg-white dark:bg-gray-800 rounded-2xl border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 text-xs sm:text-sm uppercase tracking-wider">
                        <th class="px-6 py-3 text-center font-bold text-gray-500 dark:text-gray-300">ID</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-500 dark:text-gray-300">Name</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-500 dark:text-gray-300 hidden sm:table-cell">Category</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-500 dark:text-gray-300">Price</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-500 dark:text-gray-300 hidden md:table-cell">Description</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-500 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                        <td class="px-6 py-4 text-center text-gray-900 dark:text-gray-100">{{ $product->id }}</td>
                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300 hidden sm:table-cell">{{ $product->category ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-center text-gray-900 dark:text-gray-100 font-semibold">€{{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 truncate text-gray-700 dark:text-gray-300 hidden md:table-cell">{{ $product->description ?? 'No description' }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('products.edit', $product) }}" class="text-sienna dark:text-sienna hover:text-sienna-dark font-semibold transition duration-150">Edit</a>
                            <span class="text-gray-400 dark:text-gray-600">|</span>
                            <button type="button" onclick="openDeleteModal({{ $product->id }}, '{{ addslashes($product->name) }}')" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold transition duration-150 cursor-pointer" style="background: none !important; border: none !important; outline: none !important; padding: 0 !important; margin: 0 !important; border-radius: 0 !important; box-shadow: none !important;">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 dark:text-gray-500">
                            No products found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="p-6">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 mb-4">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            
            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 text-center mb-2">
                Delete Product
            </h3>
            
            <!-- Message -->
            <p class="text-gray-600 dark:text-gray-400 text-center mb-6">
                Are you sure you want to delete <span id="productName" class="font-semibold text-gray-900 dark:text-gray-100"></span>? This action cannot be undone.
            </p>
            
            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition duration-150">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-150">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal(productId, productName) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('productName').textContent = productName;
    document.getElementById('deleteForm').action = `/products/${productId}`;
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection