@extends('layouts.app')

@section('title', 'Create Document - ' . config('app.name', 'Inventory Management'))

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        {{-- Form Container Card --}}
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl max-w-6xl mx-auto border border-gray-100 dark:border-gray-700">
            
            <h1 class="text-3xl font-extrabold text-center mb-8 text-indigo-600 dark:text-indigo-400 border-b border-gray-200 dark:border-gray-700 pb-4">
                Create New Document
            </h1>

            {{-- Error Alert (Modernized) --}}
            @if ($errors->any())
                <div class="
                    p-4 mb-6 rounded-xl border-l-4 
                    bg-red-50 dark:bg-red-900/20 
                    border-red-500 text-red-700 dark:text-red-300
                    shadow-md
                ">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('documents.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    {{-- Type Select --}}
                    <div>
                        <label for="type" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Type <span class="text-red-500">*</span></label>
                        <select name="type" id="type" 
                            class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                            <option value="estimate" {{ old('type') == 'estimate' ? 'selected' : '' }}>Estimate</option>
                            <option value="sales_order" {{ old('type') == 'sales_order' ? 'selected' : '' }}>Sales Order</option>
                            <option value="sales_invoice" {{ old('type') == 'sales_invoice' ? 'selected' : '' }}>Sales Invoice</option>
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Client Select --}}
                    <div>
                        <label for="client_id" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Client <span class="text-red-500">*</span></label>
                        <select name="client_id" id="client_id" 
                            class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                            {{-- Assuming $clients is available and populated --}}
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Invoice Date --}}
                    <div>
                        <label for="invoice_date" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Invoice Date <span class="text-red-500">*</span></label>
                        <input type="date" name="invoice_date" id="invoice_date"
                               class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                               value="{{ old('invoice_date', now()->toDateString()) }}" required>
                        @error('invoice_date')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Delivery Days --}}
                    <div>
                        <label for="delivery_days" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Delivery Days</label>
                        <input type="number" name="delivery_days" id="delivery_days"
                               class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                               min="1" value="{{ old('delivery_days') }}" placeholder="e.g. 7">
                        @error('delivery_days')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status Select --}}
                    <div>
                        <label for="status" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" id="status" 
                            class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4">
                    <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Line Items</h2>
                    
                    <div id="line-items" class="space-y-4">
                        {{-- Initial Line Item --}}
                        <div class="line-item p-5 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-700/50 shadow-inner">
                            <div class="grid md:grid-cols-4 gap-4 items-end">
                                
                                <select name="line_items[0][product_id]" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 col-span-2">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} - ${{ $product->price }}</option>
                                    @endforeach
                                </select>
                                
                                <input type="number" name="line_items[0][quantity]" placeholder="Qty" min="1" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" value="{{ old('line_items.0.quantity') ?? 1 }}">
                                
                                <div class="flex items-center space-x-2 col-span-1">
                                    <input type="number" name="line_items[0][price]" placeholder="Price" step="0.01" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" value="{{ old('line_items.0.price') }}">
                                    <button type="button" onclick="this.closest('.line-item').remove()" class="
                                        flex items-center justify-center h-12 w-12 text-red-500 dark:text-red-400 bg-gray-200 dark:bg-gray-700/50 rounded-xl 
                                        hover:bg-red-500 hover:text-white transition duration-200 shadow-md flex-shrink-0
                                    " title="Remove Item">
                                        &times;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Add Line Item Button (Secondary Style) --}}
                    <button type="button" id="add-line-item" class="
                        mt-6 px-6 py-2.5 text-base font-medium 
                        rounded-xl border border-indigo-300 text-indigo-700 dark:text-indigo-300 bg-gray-100 hover:bg-indigo-100 dark:bg-gray-700 dark:hover:bg-indigo-900/50
                        transition duration-300 shadow-md
                    ">
                        + Add Line Item
                    </button>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row justify-center items-center pt-8 space-y-4 sm:space-y-0 sm:space-x-6 border-t border-gray-200 dark:border-gray-700">
                    
                    {{-- Assuming route for index exists, otherwise, remove this link --}}
                    <a href="{{ route('documents.index') }}" class="
                        w-full sm:w-auto px-8 py-3 
                        text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600
                        bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600
                        font-bold rounded-xl shadow-md 
                        transition duration-300 ease-in-out transform hover:scale-[1.01]
                        text-center
                    ">
                        &larr; Back to Documents
                    </a>

                    <button type="submit" class="
                        w-full sm:w-auto px-8 py-3 
                        bg-indigo-600 hover:bg-indigo-700 text-white font-bold 
                        rounded-xl shadow-lg 
                        transition duration-300 ease-in-out transform hover:scale-[1.01]
                        focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50
                    ">
                        ✨ Create Document
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let lineItemCount = 1;

    // Data passed from Blade to JavaScript (make sure this variable is available globally in the script tag)
    const productsData = [
        @foreach ($products as $product)
            { id: {{ $product->id }}, name: '{{ $product->name }}', price: '{{ $product->price }}' },
        @endforeach
    ];

    const generateProductOptions = (products) => {
        return products.map(product => 
            `<option value="\${product.id}">\${product.name} - $\${product.price}</option>`
        ).join('');
    };

    const inputClass = "w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150";
    
    const newLineItemHtml = (index, productOptions) => `
        <div class="line-item p-5 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-700/50 shadow-inner">
            <div class="grid md:grid-cols-4 gap-4 items-end">
                <select name="line_items[${index}][product_id]" class="\${inputClass} col-span-2">
                    \${productOptions}
                </select>
                <input type="number" name="line_items[${index}][quantity]" placeholder="Qty" min="1" value="1" class="\${inputClass}">
                
                <div class="flex items-center space-x-2 col-span-1">
                    <input type="number" name="line_items[${index}][price]" placeholder="Price" step="0.01" class="\${inputClass}">
                    <button type="button" onclick="this.closest('.line-item').remove()" class="
                        flex items-center justify-center h-12 w-12 text-red-500 dark:text-red-400 bg-gray-200 dark:bg-gray-700/50 rounded-xl 
                        hover:bg-red-500 hover:text-white transition duration-200 shadow-md flex-shrink-0
                    " title="Remove Item">
                        &times;
                    </button>
                </div>
            </div>
        </div>
    `;

    document.addEventListener('DOMContentLoaded', function () {
        const addButton = document.getElementById('add-line-item');
        const container = document.getElementById('line-items');

        if (addButton && container) {
            addButton.addEventListener('click', function () {
                const productOptions = generateProductOptions(productsData);
                const htmlContent = newLineItemHtml(lineItemCount, productOptions);
                
                // Create a temporary div to hold the new element
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = htmlContent.trim();
                const newLineItem = tempDiv.firstChild;

                container.appendChild(newLineItem);
                lineItemCount++;
            });
        }
    });
</script>
@endpush