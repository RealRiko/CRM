@extends('layouts.app')

@section('title', 'Workers List - ' . config('app.name', 'Inventory Management'))

@section('content')
    {{-- Main Page Container --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- Card Container (Dark Mode Ready) --}}
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 max-w-6xl mx-auto">
            
            {{-- Header and Create Button --}}
            <header class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">
                    Workers List
                </h1>
                <a href="{{ route('workers.create') }}"
                    class="
                        bg-indigo-600 hover:bg-indigo-700 text-white font-semibold 
                        py-2 px-4 rounded-xl shadow-lg 
                        transition duration-300 ease-in-out transform hover:scale-[1.02]
                        text-sm sm:text-base
                    ">
                    + Create Worker
                </a>
            </header>

            {{-- Placeholder Search Input (Restored for UX) --}}
            <form method="get" action="#" class="mb-6 relative">
                <input type="text" name="search" id="search" placeholder="Search by worker name or email..."
                    class="
                        w-full p-3 pl-10 border border-gray-300 dark:border-gray-600 
                        rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                        focus:ring-indigo-500 focus:border-indigo-500 transition duration-150
                    ">
                {{-- Search Icon --}}
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>

            {{-- Session Message (Improved Styling) --}}
            @if (session('message'))
                <div class="
                    p-4 mb-6 rounded-xl border-l-4 
                    bg-green-50 dark:bg-green-900/20 
                    border-green-500 text-green-700 dark:text-green-300
                    shadow-md
                ">
                    <p class="font-medium">{{ session('message') }}</p>
                </div>
            @endif

            @if (empty($workers) || $workers->isEmpty())
                <p class="text-lg text-center text-gray-500 dark:text-gray-400 py-10">No workers found.</p>
            @else
                <div class="overflow-x-auto shadow-xl rounded-xl">
                    <table class="min-w-full bg-white dark:bg-gray-800 border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr class="text-xs sm:text-sm uppercase tracking-wider">
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300 rounded-tl-xl">Name</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Email</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-500 dark:text-gray-300 rounded-tr-xl">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($workers as $worker)
                                <tr class="hover:bg-indigo-50/50 dark:hover:bg-gray-700 transition duration-200 text-gray-900 dark:text-gray-200">
                                    {{-- Name --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $worker->name }}</td>
                                    
                                    {{-- Email --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $worker->email }}</td>
                                    
                                    {{-- Actions (Added consistent styling) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        {{-- Assuming an edit route exists for workers --}}
                                        <a href="{{ route('workers.edit', $worker->id) }}" class="
                                            text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300
                                            font-semibold transition duration-150
                                        ">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Placeholder --}}
                @if (isset($workers) && method_exists($workers, 'links'))
                    <div class="mt-8">
                        {{ $workers->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
