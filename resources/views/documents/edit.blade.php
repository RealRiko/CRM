@extends('layouts.app')

@section('title', 'Edit Document - ' . config('app.name', 'Inventory Management'))

@section('content')
    <div class="container mx-auto py-8">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-6xl mx-auto">
            <h1 class="text-3xl font-extrabold text-center mb-6 text-gray-600">Edit Document</h1>

            @if ($errors->any())
                <div class="alert alert-danger bg-gray-100 border border-gray-400 text-gray-700 px-4 py-3 rounded relative mb-6">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('documents.update', $document) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="type" class="block text-lg font-semibold text-gray-800">Type</label>
                        <select name="type" id="type" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 p-2">
                            <option value="estimate" {{ old('type', $document->type) === 'estimate' ? 'selected' : '' }}>Estimate</option>
                            <option value="sales_order" {{ old('type', $document->type) === 'sales_order' ? 'selected' : '' }}>Sales Order</option>
                            <option value="sales_invoice" {{ old('type', $document->type) === 'sales_invoice' ? 'selected' : '' }}>Sales Invoice</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-gray-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="client_id" class="block text-lg font-semibold text-gray-800">Client</label>
                        <select name="client_id" id="client_id" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 p-2">
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $document->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-sm text-gray-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="delivery_days" class="block text-lg font-semibold text-gray-800">Delivery Days</label>
                        <input type="number" name="delivery_days" id="delivery_days" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 p-2" min="1" value="{{ old('delivery_days', $document->delivery_days) }}">
                        @error('delivery_days')
                            <p class="mt-1 text-sm text-gray-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="invoice_date" class="block text-lg font-semibold text-gray-800">Invoice Date</label>
                        <input type="date" name="invoice_date" id="invoice_date" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 p-2" value="{{ old('invoice_date', $document->invoice_date ? $document->invoice_date->format('Y-m-d') : '') }}">
                        @error('invoice_date')
                            <p class="mt-1 text-sm text-gray-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-lg font-semibold text-gray-800">Status</label>
                        <select name="status" id="status" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 p-2">
                            <option value="draft" {{ old('status', $document->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="sent" {{ old('status', $document->status) === 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="paid" {{ old('status', $document->status) === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ old('status', $document->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-gray-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Line Items</h2>
                    <div id="line-items" class="space-y-4">
                        @foreach ($document->lineItems as $index => $lineItem)
                            <div class="line-item mb-4 p-4 border rounded-lg">
                                <div class="grid md:grid-cols-4 gap-4">
                                    <select name="line_items[{{ $index }}][product_id]" class="border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 p-2">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ old("line_items.{$index}.product_id", $lineItem->product_id) == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} - ${{ $product->price }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="line_items[{{ $index }}][quantity]" placeholder="Quantity" min="1" class="border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 p-2" value="{{ old("line_items.{$index}.quantity", $lineItem->quantity) }}">
                                    <input type="number" name="line_items[{{ $index }}][price]" placeholder="Price" step="0.01" class="border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 p-2" value="{{ old("line_items.{$index}.price", $lineItem->price) }}">
                                    <span class="subtotal text-right font-semibold">Subtotal: ${{ number_format($lineItem->quantity * $lineItem->price, 2) }}</span>
                                </div>
                                <button type="button" onclick="this.parentElement.remove()" class="mt-2 px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700">Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-line-item" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">Add Line Item</button>
                    <div class="mt-4 text-right">
                        <p class="text-lg font-semibold">Total: <span id="total">${{ number_format($document->lineItems->sum(function ($item) { return $item->quantity * $item->price; }), 2) }}</span></p>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                        Update Document
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let lineItemCount = {{ $document->lineItems->count() }};
    document.getElementById('add-line-item').addEventListener('click', function () {
        const container = document.getElementById('line-items');
        const newLineItem = document.createElement('div');
        newLineItem.className = 'line-item mb-4 p-4 border rounded-lg';
        newLineItem.innerHTML = `
            <div class="grid md:grid-cols-4 gap-4">
                <select name="line_items[${lineItemCount}][product_id]" class="border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 p-2">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} - ${{ $product->price }}</option>
                    @endforeach
                </select>
                <input type="number" name="line_items[${lineItemCount}][quantity]" placeholder="Quantity" min="1" class="border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 p-2">
                <input type="number" name="line_items[${lineItemCount}][price]" placeholder="Price" step="0.01" class="border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 p-2">
                <span class="subtotal text-right font-semibold">Subtotal: $0.00</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="mt-2 px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700">Remove</button>
        `;
        container.appendChild(newLineItem);
        lineItemCount++;
        updateTotal();
    });

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.line-item').forEach(item => {
            const quantity = parseInt(item.querySelector('input[name*="quantity"]').value) || 0;
            const price = parseFloat(item.querySelector('input[name*="price"]').value) || 0;
            const subtotal = quantity * price;
            item.querySelector('.subtotal').textContent = 'Subtotal: $' + subtotal.toFixed(2);
            total += subtotal;
        });
        document.getElementById('total').textContent = '$' + total.toFixed(2);
    }

    document.addEventListener('input', updateTotal);
    updateTotal();
</script>
@endpush