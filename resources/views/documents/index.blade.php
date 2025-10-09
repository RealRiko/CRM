@extends('layouts.app')

@section('title', 'Documents - ' . config('app.name', 'Inventory Management'))

@section('content')
    {{-- Main Page Container --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- Card Container (Dark Mode Ready) --}}
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700">
            
            {{-- Header and Create Button --}}
<header class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">
                     Documents List
                </h1>
                <a href="{{ route('documents.create') }}"
                    class="
                        bg-indigo-600 hover:bg-indigo-700 text-white font-semibold 
                        py-2 px-4 rounded-xl shadow-lg 
                        transition duration-300 ease-in-out transform hover:scale-[1.02]
                        text-sm sm:text-base
                    ">
                    + Create Document
                </a>
            </header>

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

            {{-- Search Input (Modernized) --}}
            <form method="get" action="{{ route('documents.index') }}" class="mb-6 relative">
                <input type="text" name="search" id="search" placeholder="Search by client or type..."
                    class="
                        w-full p-3 pl-10 border border-gray-300 dark:border-gray-600 
                        rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                        focus:ring-indigo-500 focus:border-indigo-500 transition duration-150
                    "
                    value="{{ request('search') }}" onkeyup="this.form.submit()">
                {{-- Search Icon --}}
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>

            @if ($documents->isEmpty())
                <p class="text-lg text-center text-gray-500 dark:text-gray-400 py-10">No documents found.</p>
            @else
                <div class="overflow-x-auto shadow-xl rounded-xl">
                    <table class="min-w-full bg-white dark:bg-gray-800 border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr class="text-xs sm:text-sm uppercase tracking-wider">
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300 rounded-tl-xl">Type</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Client</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300 hidden md:table-cell">Delivery Days</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Total</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Status</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300 hidden lg:table-cell">Created At</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-500 dark:text-gray-300 rounded-tr-xl">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($documents as $document)
                                <tr class="hover:bg-indigo-50/50 dark:hover:bg-gray-700 transition duration-200 text-gray-900 dark:text-gray-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ ucfirst($document->type) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $document->client->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $document->delivery_days }} days</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">${{ number_format($document->total, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- Modern Status Badge --}}
                                        <span class="
                                            px-3 py-1 rounded-full text-xs font-bold 
                                            {{ $document->status == 'paid' 
                                                ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200' 
                                                : ($document->status == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200') }}
                                        ">
                                            {{ ucfirst($document->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell">{{ $document->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-2">
                                        <a href="{{ route('documents.edit', $document) }}" class="
                                            text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300
                                            font-semibold transition duration-150
                                        ">Edit</a>
                                        <span class="text-gray-400 dark:text-gray-600">|</span>
                                        <a href="{{ route('documents.pdf', $document) }}" class="
                                            text-purple-600 dark:text-purple-400 hover:text-purple-900 dark:hover:text-purple-300
                                            font-semibold transition duration-150
                                        ">PDF</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if (isset($documents) && method_exists($documents, 'links'))
                    <div class="mt-8">
                        {{ $documents->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection