@extends('layouts.app')

@section('content')

<div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl max-w-5xl mx-auto border border-gray-100 dark:border-gray-700 mt-6">

    <h1 class="text-3xl font-extrabold text-center mb-8 text-amber-600 dark:text-amber-400 border-b border-gray-200 dark:border-gray-700 pb-4">
        {{ isset($document) ? 'Edit Document: ' . ($document->type ?? 'N/A') : 'Create New Document' }}
    </h1>

    @if (session('success'))
        <div class="p-4 mb-6 rounded-xl border-l-4 bg-green-50 dark:bg-green-900/20 border-green-500 text-green-700 dark:text-green-300 shadow-md">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 mb-6 rounded-xl border-l-4 bg-red-50 dark:bg-red-900/20 border-red-500 text-red-700 dark:text-red-300 shadow-md">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="documentForm" action="{{ isset($document) ? route('documents.update', $document) : route('documents.store') }}" method="POST" class="space-y-6">
        @csrf
        @if (isset($document))
            @method('PATCH')
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Document Type -->
            <div>
                <label for="type" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="type" id="type" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('type') border-red-500 @enderror" required>
                    <option value="estimate" {{ old('type', $document->type ?? '') == 'estimate' ? 'selected' : '' }}>Estimate</option>
                    <option value="sales_order" {{ old('type', $document->type ?? '') == 'sales_order' ? 'selected' : '' }}>Sales Order</option>
                    <option value="sales_invoice" {{ old('type', $document->type ?? '') == 'sales_invoice' ? 'selected' : '' }}>Sales Invoice</option>
                </select>
                @error('type')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('status') border-red-500 @enderror" required></select>
                @error('status')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Client -->
            <div>
                <label for="client_id" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Client <span class="text-red-500">*</span></label>
                <select name="client_id" id="client_id" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('client_id') border-red-500 @enderror" required>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $document->client_id ?? '') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
                @error('client_id')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Invoice Date -->
            <div>
                <label for="invoice_date" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Invoice Date <span class="text-red-500">*</span></label>
                <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', $document->invoice_date ?? '') }}" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('invoice_date') border-red-500 @enderror" required>
                @error('invoice_date')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Delivery Days -->
            <div>
                <label for="delivery_days" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Delivery Days <span class="text-red-500">*</span></label>
                <input type="number" name="delivery_days" id="delivery_days" min="1" value="{{ old('delivery_days', $document->delivery_days ?? '') }}" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('delivery_days') border-red-500 @enderror" required>
                @error('delivery_days')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Line Items Section -->
        <div>
            <h2 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Line Items</h2>

            <div id="stock-warning" class="hidden mb-4 p-4 rounded-xl border-l-4 shadow-md">
                <p id="warning-message" class="font-medium"></p>
            </div>

            <div class="flex items-center justify-end gap-2 mb-4">
                <span class="text-gray-700 dark:text-gray-300 font-semibold">VAT 21%</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="apply_vat" class="sr-only peer" checked>
                    <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-500 rounded-full peer dark:bg-gray-600 peer-checked:bg-amber-600 transition-all"></div>
                    <span class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transition-all peer-checked:translate-x-6"></span>
                </label>
            </div>

            <div id="line-items" class="space-y-3">
                @php
                    if (old('line_items')) {
                        $lineItems = old('line_items');
                    } elseif (isset($document) && $document->lineItems && $document->lineItems->count() > 0) {
                        $lineItems = $document->lineItems->map(function($item) {
                            return [
                                'product_id' => $item->product_id,
                                'quantity' => $item->quantity,
                                'price' => $item->price,
                            ];
                        })->toArray();
                    } else {
                        $lineItems = [[]];
                    }
                @endphp

                @foreach ($lineItems as $index => $item)
                    <div class="line-item bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-600">
                        <div class="flex flex-col sm:flex-row items-start sm:items-end gap-3">
                            <div class="flex-1 w-full min-w-0">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product</label>
                                <select name="line_items[{{ $index }}][product_id]" class="product-select w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-150 text-sm" data-original-qty="{{ $item['quantity'] ?? 0 }}" required>
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" {{ old("line_items.$index.product_id", $item['product_id'] ?? '') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (Stock: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full sm:w-28 flex-shrink-0">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                                <input type="number" name="line_items[{{ $index }}][quantity]" value="{{ old("line_items.$index.quantity", $item['quantity'] ?? '') }}" class="quantity-input w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-150 text-sm" min="1" placeholder="Qty" data-original-qty="{{ $item['quantity'] ?? 0 }}" required>
                            </div>

                            <div class="w-full sm:w-32 flex-shrink-0">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price</label>
                                <input type="number" name="line_items[{{ $index }}][price]" value="{{ old("line_items.$index.price", $item['price'] ?? '') }}" class="price-input w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-150 text-sm" step="0.01" min="0" placeholder="0.00" data-base-price="{{ $item['price'] ?? '' }}" required>
                            </div>

                            @if($index > 0)
                            <div class="flex-shrink-0">
                                <button type="button" class="remove-line-item p-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition duration-150 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            @else
                            <div class="flex-shrink-0 w-11"></div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-line-item" class="mt-4 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl shadow-lg transition duration-300 ease-in-out transform hover:scale-[1.02] flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Line Item
            </button>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center pt-6 space-y-4 sm:space-y-0 sm:space-x-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('documents.index') }}" class="w-full sm:w-auto px-8 py-3 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 font-semibold rounded-xl shadow-md transition duration-300 ease-in-out transform hover:scale-[1.02] text-center">
                &larr; Back to List
            </a>
            <button type="submit" id="submitBtn" class="w-full sm:w-auto px-8 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl shadow-lg transition duration-300 ease-in-out transform hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-amber-500 focus:ring-opacity-50">
                {{ isset($document) ? 'Update Document' : 'Create Document' }}
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- Status Dropdown ---
    const typeSelect = document.getElementById('type');
    const statusSelect = document.getElementById('status');
    const currentStatus = "{{ old('status', $document->status ?? '') }}";

    function populateStatusOptions() {
        let options = [];
        const type = typeSelect.value;

        if (type === 'estimate') {
            options = [
                {value: 'draft', text: 'Draft'},
                {value: 'sent', text: 'Sent'}
            ];
        } else if (type === 'sales_order') {
            options = [
                {value: 'draft', text: 'Draft'},
                {value: 'confirmed', text: 'Confirmed'},
                {value: 'cancelled', text: 'Cancelled'}
            ];
        } else if (type === 'sales_invoice') {
            options = [
                {value: 'waiting_payment', text: 'Waiting for Payment'},
                {value: 'paid', text: 'Paid'}
            ];
        }

        statusSelect.innerHTML = '';
        options.forEach(opt => {
            const optionEl = document.createElement('option');
            optionEl.value = opt.value;
            optionEl.textContent = opt.text;
            if (opt.value === currentStatus) optionEl.selected = true;
            statusSelect.appendChild(optionEl);
        });
    }

    populateStatusOptions();
    typeSelect.addEventListener('change', populateStatusOptions);

    // --- Line Items ---
    const container = document.getElementById('line-items');
    const addBtn = document.getElementById('add-line-item');
    const stockWarning = document.getElementById('stock-warning');
    const warningMessage = document.getElementById('warning-message');
    const vatToggle = document.getElementById('apply_vat');

    let lineIndex = {{ count(old('line_items', isset($document) && $document->lineItems ? $document->lineItems : [[]])) }};

    function showWarning(message, type = 'error') {
        warningMessage.textContent = message;
        stockWarning.className = 'hidden';
        if (type === 'warning') {
            stockWarning.className = 'mb-4 p-4 rounded-xl border-l-4 shadow-md bg-yellow-50 dark:bg-yellow-900/20 border-yellow-500 text-yellow-700 dark:text-yellow-300';
        } else {
            stockWarning.className = 'mb-4 p-4 rounded-xl border-l-4 shadow-md bg-red-50 dark:bg-red-900/20 border-red-500 text-red-700 dark:text-red-300';
        }
    }

    function hideWarning() {
        stockWarning.classList.add('hidden');
    }

    function validateAllLineItems() {
        const lineItems = container.querySelectorAll('.line-item');
        let warnings = [];

        lineItems.forEach(line => {
            const productSelect = line.querySelector('.product-select');
            const quantityInput = line.querySelector('.quantity-input');
            const selected = productSelect.options[productSelect.selectedIndex];

            line.classList.remove('border-red-400', 'border-yellow-400');

            if (!selected.value) return;

            const currentStock = parseInt(selected.getAttribute('data-stock')) || 0;
            const originalQty = parseInt(quantityInput.getAttribute('data-original-qty')) || 0;
            const newQty = parseInt(quantityInput.value) || 0;
            const productName = selected.textContent.split('(')[0].trim();
            const availableStock = currentStock + originalQty;

            if (availableStock <= 0) {
                warnings.push(`${productName} is OUT OF STOCK (available: ${availableStock})`);
                line.classList.add('border-red-400');
            } else if (newQty > availableStock) {
                warnings.push(`${productName}: Quantity (${newQty}) exceeds stock (${availableStock})`);
                line.classList.add('border-yellow-400');
            }
        });

        if (warnings.length) {
            showWarning('⚠️ ' + warnings.join(' | '), 'warning');
        } else {
            hideWarning();
        }
    }

    function updatePricesWithVAT() {
        const applyVAT = vatToggle.checked;
        const lineItems = container.querySelectorAll('.line-item');

        lineItems.forEach(line => {
            const priceInput = line.querySelector('.price-input');
            const basePrice = parseFloat(priceInput.getAttribute('data-base-price')) || parseFloat(priceInput.value) || 0;
            priceInput.value = applyVAT ? (basePrice * 1.21).toFixed(2) : basePrice.toFixed(2);
        });
    }

    container.addEventListener('change', e => {
        if (e.target.classList.contains('product-select')) {
            const line = e.target.closest('.line-item');
            const priceInput = line.querySelector('.price-input');
            const selected = e.target.options[e.target.selectedIndex];
            const price = parseFloat(selected.getAttribute('data-price')) || 0;

            if (!priceInput.value) priceInput.value = price.toFixed(2);
            priceInput.setAttribute('data-base-price', price);

            validateAllLineItems();
            updatePricesWithVAT();
        }
    });

    vatToggle.addEventListener('change', updatePricesWithVAT);

    let validationTimeout;
    container.addEventListener('input', e => {
        if (e.target.classList.contains('quantity-input')) {
            clearTimeout(validationTimeout);
            validationTimeout = setTimeout(() => validateAllLineItems(), 300);
        }
    });

    addBtn.addEventListener('click', () => {
        const template = container.querySelector('.line-item');
        const newLine = template.cloneNode(true);

        newLine.querySelectorAll('input, select').forEach(el => {
            el.name = el.name.replace(/\d+/, lineIndex);
            if (el.tagName === 'INPUT') el.value = '';
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            el.setAttribute('data-original-qty', '0');
        });

        const oldRemoveBtn = newLine.querySelector('.remove-line-item');
        if (oldRemoveBtn) oldRemoveBtn.remove();

        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'flex-shrink-0';
        buttonContainer.innerHTML = `
            <button type="button" class="remove-line-item p-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition duration-150 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        newLine.querySelector('.flex').appendChild(buttonContainer);

        container.appendChild(newLine);
        lineIndex++;
        updatePricesWithVAT();
    });

    container.addEventListener('click', e => {
        if (e.target.closest('.remove-line-item')) {
            if (container.querySelectorAll('.line-item').length > 1) {
                e.target.closest('.line-item').remove();
                validateAllLineItems();
                updatePricesWithVAT();
            }
        }
    });

    setTimeout(() => {
        validateAllLineItems();
        updatePricesWithVAT();
    }, 100);
});
</script>
@endpush
