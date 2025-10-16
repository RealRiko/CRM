@extends('layouts.app')

@section('title', 'Clients - ' . config('app.name', 'Inventory Management'))

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

        {{-- Card Container --}}
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700">

            {{-- Header --}}
            <header class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">
                    Client Directory
                </h1>
                <a href="{{ route('clients.create') }}"
                   class="bg-amber-sienna hover:bg-amber-sienna-dark text-white font-semibold py-2 px-4 rounded-xl shadow-lg transition duration-300 ease-in-out transform hover:scale-[1.02] text-sm sm:text-base">
                    + Add New Client
                </a>
            </header>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="p-4 mb-6 rounded-xl border-l-4 bg-green-50 dark:bg-green-900/20 border-green-500 text-green-700 dark:text-green-300 shadow-md">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Search Input --}}
            <form method="GET" action="{{ route('clients.index') }}" class="mb-8 relative">
                <input type="text" id="live-search-input" name="search" placeholder="Search clients by name, email, or phone..."
                       value="{{ request('search') }}"
                       class="w-full p-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-amber-sienna focus:border-amber-sienna transition duration-150">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>

            {{-- Client Table --}}
            <div id="client-list-container" class="overflow-hidden rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border-collapse">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr class="text-xs sm:text-sm uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3 hidden md:table-cell">Phone</th>
                                <th class="px-6 py-3 hidden lg:table-cell">Address</th>
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($clients as $client)
                                <tr class="hover:bg-amber-50/50 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $client->name }}</td>
                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $client->email }}</td>
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $client->phone ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400 hidden lg:table-cell">{{ $client->address ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('clients.edit', $client) }}"
                                           class="text-amber-sienna hover:text-amber-sienna-dark font-semibold transition duration-150">
                                           Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- ✅ Corrected colspan: exactly 5 columns total --}}
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 dark:text-gray-500">
                                        <svg class="inline-block w-8 h-8 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                        </svg>
                                        No clients found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if (isset($clients) && method_exists($clients, 'links'))
                    <div class="mt-6 px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 rounded-b-xl">
                        {{ $clients->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
