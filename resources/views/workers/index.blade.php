@extends('layouts.app')

@section('title', 'Workers List - ' . config('app.name', 'Inventory Management'))

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

    {{-- Main Page Container --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- Card Container (Dark Mode Ready) --}}
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 max-w-6xl mx-auto">
            
            {{-- Header and Create Button (Amber Themed) --}}
            <header class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">
                    Workers List
                </h1>
                <a href="{{ route('workers.create') }}"
                    class="
                        {{-- AMBER BUTTON CLASS APPLIED --}}
                        bg-amber-sienna hover:bg-amber-sienna-dark text-white font-semibold 
                        py-2 px-4 rounded-xl shadow-lg 
                        transition duration-300 ease-in-out transform hover:scale-[1.02]
                        text-sm sm:text-base
                    ">
                    + Create Worker
                </a>
            </header>

            {{-- Placeholder Success Alert (Using 'success' session key for consistency) --}}
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

            @if (session('error'))
                <div class="p-4 mb-6 rounded-xl border-l-4 bg-red-50 dark:bg-red-900/20 border-red-500 text-red-700 dark:text-red-300 shadow-md">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Search Input (Amber Focus Applied) --}}
            <form method="get" action="#" class="mb-6 relative">
                <input type="text" name="search" id="search" placeholder="Search by worker name or email..."
                    class="
                        w-full p-3 pl-10 border border-gray-300 dark:border-gray-600 
                        rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                        {{-- AMBER FOCUS RING APPLIED --}}
                        focus:ring-2 focus:ring-amber-sienna focus:border-amber-sienna transition duration-150
                    ">
                {{-- Search Icon --}}
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>

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
                                {{-- HOVER: Gray instead of amber --}}
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200 text-gray-900 dark:text-gray-200">
                                    {{-- Name --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $worker->name }}</td>
                                    
                                    {{-- Email --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $worker->email }}</td>
                                    
                                    {{-- Actions (Amber Themed) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-2">
                                        {{-- AMBER ACTION LINK APPLIED --}}
                                        <a href="{{ route('workers.edit', $worker->id) }}" class="
                                            text-amber-sienna dark:text-amber-sienna hover:text-amber-sienna-dark dark:hover:text-amber-sienna-dark
                                            font-semibold transition duration-150
                                        ">Edit</a>
                                        
                                        @if (auth()->id() !== $worker->id)
                                            <span class="text-gray-400 dark:text-gray-600">|</span>
                                            <button type="button" onclick="openDeleteModal({{ $worker->id }}, '{{ addslashes($worker->name) }}')" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold transition duration-150 cursor-pointer" style="background: none !important; border: none !important; outline: none !important; padding: 0 !important; margin: 0 !important; border-radius: 0 !important; box-shadow: none !important;">
                                                Delete
                                            </button>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-600">|</span>
                                            <span class="text-gray-400 dark:text-gray-500 font-semibold cursor-not-allowed">Delete</span>
                                        @endif
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 mb-4">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                
                <!-- Title -->
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 text-center mb-2">
                    Delete Worker
                </h3>
                
                <!-- Message -->
                <p class="text-gray-600 dark:text-gray-400 text-center mb-6">
                    Are you sure you want to delete <span id="workerName" class="font-semibold text-gray-900 dark:text-gray-100"></span>? This action cannot be undone.
                </p>
                
                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition duration-150">
                        Cancel
                    </button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-150">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function openDeleteModal(workerId, workerName) {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('workerName').textContent = workerName;
        document.getElementById('deleteForm').action = `/workers/${workerId}`;
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
    </script>
@endsection