   @extends('layouts.app')

   @section('title', 'Invoice #' . $invoice->id . ' - ' . config('app.name', 'Inventory Management'))

   @section('content')
       <div class="container mx-auto py-8">
           <div class="bg-white p-6 rounded-lg shadow-lg max-w-6xl mx-auto">
               <h1 class="text-3xl font-extrabold text-center mb-6 text-indigo-600">Invoice #{{ $invoice->id }}</h1>

               <div class="mb-6">
                   <p><strong>Client:</strong> {{ $invoice->client->name }}</p>
                   <p><strong>Date:</strong> {{ $invoice->invoice_date->format('Y-m-d') }}</p>
                   <p><strong>Due Date:</strong> {{ $invoice->due_date->format('Y-m-d') }}</p>
                   <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
                   <p><strong>Total:</strong> ${{ number_format($invoice->total, 2) }}</p>
               </div>

               <div class="mb-6">
                   <h2 class="text-xl font-semibold mb-4">Line Items</h2>
                   <div class="overflow-x-auto">
                       <table class="min-w-full divide-y divide-gray-200">
                           <thead class="bg-gray-50">
                               <tr>
                                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                               </tr>
                           </thead>
                           <tbody class="bg-white divide-y divide-gray-200">
                               @foreach ($invoice->lineItems as $item)
                                   <tr>
                                       <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->name }}</td>
                                       <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                       <td class="px-6 py-4 whitespace-nowrap">${{ number_format($item->price, 2) }}</td>
                                       <td class="px-6 py-4 whitespace-nowrap">${{ number_format($item->subtotal, 2) }}</td>
                                   </tr>
                               @endforeach
                           </tbody>
                       </table>
                   </div>
               </div>

               <div class="text-center">
                   <a href="{{ route('documents.invoices') }}" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Back to Invoices</a>
               </div>
           </div>
       </div>
   @endsection