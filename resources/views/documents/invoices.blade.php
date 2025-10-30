@extends('layouts.app')

@section('title', 'Invoices - ' . config('app.name', 'Inventory Management'))

@section('content')
    {{-- Custom Amber Color (Sienna Amber: #CA8A04) --}}
    <style>
        .text-amber-sienna { color: #CA8A04; }
        .border-amber-sienna { border-color: #CA8A04; }
        .bg-amber-sienna { background-color: #CA8A04; }
        .hover\:bg-amber-sienna-dark:hover { background-color: #A16207; }
        .focus\:ring-amber-sienna:focus { --tw-ring-color: #CA8A04; }
        .focus\:border-amber-sienna:focus { border-color: #CA8A04; }
    </style>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 max-w-6xl mx-auto">
            
            <header class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">
                    Invoices List
                </h1>
            </header>

            @if (session('success'))
                <div class="p-4 mb-6 rounded-xl border-l-4 bg-green-50 dark:bg-green-900/20 border-green-500 text-green-700 dark:text-green-300 shadow-md">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <form method="get" action="#" class="mb-6 relative">
                <input type="text" name="search" id="search" placeholder="Search by invoice ID or client..."
                    class="w-full p-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-amber-sienna focus:border-amber-sienna transition duration-150">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>

            @if (isset($invoices) && $invoices->isEmpty())
                <p class="text-lg text-center text-gray-500 dark:text-gray-400 py-10">No invoices found.</p>
            @else
                {{-- Fixed overflow and rounded corners --}}
                <div class="overflow-x-auto shadow-xl rounded-xl px-2">
                    <table class="min-w-full w-full bg-white dark:bg-gray-800 border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr class="text-xs sm:text-sm uppercase tracking-wider">
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300 rounded-tl-xl">ID</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Client</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Date</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-500 dark:text-gray-300">Status</th>
                                <th class="px-6 py-3 font-semibold text-gray-500 dark:text-gray-300">
                                    <div class="text-center">Total (€)</div>
                                </th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-500 dark:text-gray-300 rounded-tr-xl">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($invoices as $invoice)
                                <tr class="hover:bg-amber-50/50 dark:hover:bg-gray-700 transition duration-200 text-gray-900 dark:text-gray-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">#{{ $invoice->id }}</td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('clients.show', $invoice->client) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                            {{ $invoice->client->name ?? 'N/A' }}
                                        </a>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold 
                                            {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200' 
                                            : ($invoice->status == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200' 
                                            : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200') }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600 dark:text-green-400">
                                        <div class="text-center">€{{ number_format($invoice->total, 2, ',', '.') }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <a href="{{ route('documents.show', $invoice->id) }}" class="text-amber-sienna dark:text-amber-sienna hover:text-amber-sienna-dark dark:hover:text-amber-sienna-dark font-semibold transition duration-150 p-2 rounded-lg">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if (isset($invoices) && method_exists($invoices, 'links'))
                    <div class="mt-8">
                        {{ $invoices->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
