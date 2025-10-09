@extends('layouts.app')

@section('title', 'Create Document - ' . config('app.name', 'Inventory Management'))

@section('content')
    <div class="container mx-auto py-8">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-6xl mx-auto">
            <h1 class="text-3xl font-extrabold text-center mb-6 text-indigo-600">Create New Document</h1>

            @if ($errors->any())
                <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('documents.store') }}" method="POST">
                @csrf

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="type" class="block text-lg font-semibold text-gray-800">Type</label>
                        <select name="type" id="type" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="estimate">Estimate</option>
                            <option value="sales_order">Sales Order</option>
                            <option value="sales_invoice">Sales Invoice</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="client_id" class="block text-lg font-semibold text-gray-800">Client</label>
                        <select name="client_id" id="client_id" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="invoice_date" class="block text-lg font-semibold text-gray-800">Invoice Date</label>
                        <input type="date" name="invoice_date" id="invoice_date"
                               class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('invoice_date', now()->toDateString()) }}">
                        @error('invoice_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="delivery_days" class="block text-lg font-semibold text-gray-800">Delivery Days</label>
                        <input type="number" name="delivery_days" id="delivery_days"
                               class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               min="1" value="{{ old('delivery_days') }}">
                        @error('delivery_days')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-lg font-semibold text-gray-800">Status</label>
                        <select name="status" id="status" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="draft">Draft</option>
                            <option value="sent">Sent</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Line Items</h2>
                    <div id="line-items">
                        <div class="line-item mb-4 p-4 border rounded-lg">
                            <div class="grid md:grid-cols-4 gap-4">
                                <select name="line_items[0][product_id]" class="border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} - ${{ $product->price }}</option>
                                    @endforeach
                                </select>
                                <input type="number" name="line_items[0][quantity]" placeholder="Quantity" min="1" class="border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <input type="number" name="line_items[0][price]" placeholder="Price" step="0.01" class="border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <span class="subtotal text-right font-semibold">Subtotal: $0.00</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-line-item" class="px-4 py-2 bg-green-600 text-white rounded-lg">Add Line Item</button>
                    <div class="mt-4 text-right">
                        <p class="text-lg font-semibold">Total: <span id="total">$0.00</span></p>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Create Document
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let lineItemCount = 1;
    document.getElementById('add-line-item').addEventListener('click', function () {
        const container = document.getElementById('line-items');
        const newLineItem = document.createElement('div');
        newLineItem.className = 'line-item mb-4 p-4 border rounded-lg';
        newLineItem.innerHTML = `
            <div class="grid md:grid-cols-4 gap-4">
                <select name="line_items[${lineItemCount}][product_id]" class="border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} - ${{ $product->price }}</option>
                    @endforeach
                </select>
                <input type="number" name="line_items[${lineItemCount}][quantity]" placeholder="Quantity" min="1" class="border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <input type="number" name="line_items[${lineItemCount}][price]" placeholder="Price" step="0.01" class="border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <span class="subtotal text-right font-semibold">Subtotal: $0.00</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="mt-2 px-3 py-1 bg-red-600 text-white rounded">Remove</button>
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
</script>
@endpush
