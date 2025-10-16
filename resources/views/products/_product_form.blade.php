{{-- 
    This partial contains the complete form logic for both Create and Update.
    It expects an optional $product variable to be present. 
--}}
<div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl max-w-4xl mx-auto border border-gray-100 dark:border-gray-700">
            
    {{-- Dynamic Header Text --}}
    <h1 class="text-3xl font-extrabold text-center mb-8 text-amber-sienna dark:text-amber-sienna-light border-b border-gray-200 dark:border-gray-700 pb-4">
        {{ isset($product) ? 'Edit Product: ' . $product->name : 'Create New Product' }}
    </h1>

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

    {{-- Form Setup --}}
    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if (isset($product))
            @method('PATCH')
        @endif

        {{-- Name Field --}}
        <div>
            <label for="name" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Product Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" 
                class="
                    w-full p-3 border border-gray-300 dark:border-gray-600 
                    rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                    focus:ring-amber-sienna focus:border-amber-sienna transition duration-150
                    @error('name') border-red-500 @enderror
                " 
                value="{{ old('name', $product->name ?? '') }}" 
                required>
            @error('name')
                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Price Field --}}
        <div>
            <label for="price" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Price (€) <span class="text-red-500">*</span></label>
            <input type="number" name="price" id="price" step="0.01" 
                class="
                    w-full p-3 border border-gray-300 dark:border-gray-600 
                    rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                    focus:ring-amber-sienna focus:border-amber-sienna transition duration-150
                    @error('price') border-red-500 @enderror
                " 
                value="{{ old('price', $product->price ?? '') }}" 
                required min="0">
            @error('price')
                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description Field --}}
        <div>
            <label for="description" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Description</label>
            <textarea name="description" id="description" rows="5" 
                class="
                    w-full p-3 border border-gray-300 dark:border-gray-600 
                    rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                    focus:ring-amber-sienna focus:border-amber-sienna transition duration-150
                    @error('description') border-red-500 @enderror
                "
            >{{ old('description', $product->description ?? '') }}</textarea>
            @error('description')
                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Stock Field --}}
        <div>
            <label for="stock" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Stock Quantity</label>
            <input type="number" name="stock" id="stock" 
                class="
                    w-full p-3 border border-gray-300 dark:border-gray-600 
                    rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                    focus:ring-amber-sienna focus:border-amber-sienna transition duration-150
                    @error('stock') border-red-500 @enderror
                " 
                value="{{ old('stock', $product->stock ?? 0) }}" 
                min="0">
            @error('stock')
                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Category Field --}}
        <div>
            <label for="category" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Category</label>
            <input type="text" name="category" id="category" 
                class="
                    w-full p-3 border border-gray-300 dark:border-gray-600 
                    rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                    focus:ring-amber-sienna focus:border-amber-sienna transition duration-150
                    @error('category') border-red-500 @enderror
                " 
                value="{{ old('category', $product->category ?? '') }}">
            @error('category')
                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row justify-between items-center pt-4 space-y-4 sm:space-y-0 sm:space-x-4">
            
            <a href="{{ route('products.index') }}" class="
                w-full sm:w-auto px-8 py-3 
                text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600
                bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600
                font-bold rounded-xl shadow-md 
                transition duration-300 ease-in-out transform hover:scale-[1.01]
                text-center
            ">
                &larr; Back to List
            </a>

            {{-- Primary Button: Amber Sienna theme --}}
            <button type="submit" class="
                w-full sm:w-auto px-8 py-3 
                bg-amber-sienna hover:bg-amber-sienna-dark text-white font-bold 
                rounded-xl shadow-lg 
                transition duration-300 ease-in-out transform hover:scale-[1.01]
                focus:outline-none focus:ring-4 focus:ring-amber-sienna focus:ring-opacity-50
            ">
                {{ isset($product) ? 'Update Product' : 'Create Product' }}
            </button>
        </div>
    </form>
</div>
