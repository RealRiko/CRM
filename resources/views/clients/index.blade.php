@extends('layouts.app')

@section('title', 'Clients - ' . config('app.name', 'Inventory Management'))

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header Section & Create Button --}}
        <header class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">
                + Client List
            </h1>
            <a href="{{ route('clients.create') }}" class="
                bg-indigo-600 hover:bg-indigo-700 text-white font-semibold 
                py-2 px-4 rounded-xl shadow-lg 
                transition duration-300 ease-in-out transform hover:scale-[1.02]
                text-sm sm:text-base
            ">
                + Add New Client
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
        <div class="mb-8">
            <form method="GET" action="{{ route('clients.index') }}" class="relative">
                <input type="text" name="search" placeholder="Search clients by name..."
                        value="{{ request('search') }}"
                        class="
                            w-full p-3 pl-10 border border-gray-300 dark:border-gray-600 
                            rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                            focus:ring-indigo-500 focus:border-indigo-500 
                            transition duration-150
                        "
                        onkeyup="this.form.submit()">
                
                {{-- Search Icon --}}
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
        </div>

        {{-- Client Table (High-Quality Design with Dark Mode) --}}
        <div class="overflow-x-auto shadow-2xl rounded-2xl">
            <table class="min-w-full 
                bg-white dark:bg-gray-800 
                rounded-2xl 
                overflow-hidden
                border-collapse
            ">
                <thead>
                    <tr class="
                        bg-gray-50 dark:bg-gray-700 
                        border-b border-gray-200 dark:border-gray-600
                        text-xs sm:text-sm uppercase tracking-wider
                    ">
                        <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Name</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Email</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300 hidden sm:table-cell">Phone</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-500 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr class="
                            border-b border-gray-100 dark:border-gray-700 
                            hover:bg-indigo-50/50 dark:hover:bg-gray-700 
                            transition duration-150
                        ">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $client->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $client->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">{{ $client->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                {{-- Assuming you have an edit route for clients --}}
                                <a href="{{ route('clients.edit', $client) }}" class="
                                    text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300
                                    font-semibold transition duration-150
                                ">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-lg text-gray-400 dark:text-gray-500">
                                <svg class="inline-block w-8 h-8 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                No clients found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination (If clients variable is a Paginator instance) --}}
        @if (isset($clients) && method_exists($clients, 'links'))
            <div class="mt-8">
                {{ $clients->links() }}
            </div>
        @endif
        
    </div>
@endsection