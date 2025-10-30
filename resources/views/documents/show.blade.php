@extends('layouts.app')

@section('title', 'Document #' . $document->id . ' - ' . config('app.name', 'Inventory Management'))

@section('content')
    <div class="container mx-auto py-10 sm:py-12">
        {{-- Document Card Container --}}
        <div class="max-w-4xl mx-auto p-6 sm:p-10 rounded-2xl shadow-xl 
                    bg-white dark:bg-gray-800 
                    border border-gray-100 dark:border-gray-700">

            {{-- Document Header (Title) --}}
            <header class="text-center mb-8 border-b pb-4 border-gray-200 dark:border-gray-700">
                <h1 class="text-4xl font-extrabold text-amber-sienna dark:text-amber-400 mb-2">
                    DOCUMENT OVERVIEW
                </h1>
                <p class="text-xl font-semibold text-gray-700 dark:text-gray-300">
                    Invoice/Document ID: <span class="text-gray-900 dark:text-gray-100">#{{ $document->id }}</span>
                </p>
            </header>

            {{-- Document Details Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 dark:text-gray-300">
                
                {{-- Client Information --}}
                <div class="p-4 border-l-4 border-amber-sienna dark:border-amber-400 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-lg font-bold mb-2 text-gray-800 dark:text-gray-100">Client Details</p>
                    <p><strong>Client Name:</strong> <span class="font-medium">{{ $document->client->name }}</span></p>
                </div>
                
                {{-- Financial & Status Information --}}
                <div class="p-4 border-l-4 border-amber-sienna dark:border-amber-400 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-lg font-bold mb-2 text-gray-800 dark:text-gray-100">Financial Summary</p>
                    <p><strong>Total Amount:</strong> <span class="font-extrabold text-xl text-amber-sienna dark:text-amber-400">€{{ number_format($document->total, 2) }}</span></p>
                </div>

                {{-- Date Information --}}
                <div class="p-4 md:col-span-2 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-inner">
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <p><strong>Document Date:</strong> <br><span class="font-medium">{{ $document->invoice_date->format('Y-m-d') }}</span></p>
                        <p><strong>Due Date:</strong> <br><span class="font-medium text-red-500 dark:text-red-400">{{ $document->due_date->format('Y-m-d') }}</span></p>
                        <p><strong>Status:</strong> <br>
                            @php
                                $statusClass = [
                                    'paid' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300',
                                    'pending' => 'bg-amber-200 text-amber-900 dark:bg-amber-800 dark:text-amber-200',
                                    'draft' => 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-100',
                                ][$document->status] ?? 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-100';
                            @endphp
                            <span class="inline-block mt-1 px-3 py-1 text-xs font-semibold uppercase tracking-wider rounded-full {{ $statusClass }}">
                                {{ ucfirst($document->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
