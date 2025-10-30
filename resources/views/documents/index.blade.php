@extends('layouts.app')

@section('title', 'Documents - ' . config('app.name', 'Inventory Management'))

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
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700">

            <header class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">
                    Documents List
                </h1>
                <a href="{{ route('documents.create') }}"
                    class="bg-amber-sienna hover:bg-amber-sienna-dark text-white font-semibold py-2 px-4 rounded-xl shadow-lg transition duration-300 ease-in-out transform hover:scale-[1.02] text-sm sm:text-base">
                    + Create Document
                </a>
            </header>

            @if (session('success'))
                <div class="p-4 mb-6 rounded-xl border-l-4 bg-green-50 dark:bg-green-900/20 border-green-500 text-green-700 dark:text-green-300 shadow-md">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <form method="get" action="{{ route('documents.index') }}" class="mb-6 relative">
                <input type="text" name="search" id="search" placeholder="Search by client or type..."
                    class="w-full p-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-amber-sienna focus:border-amber-sienna transition duration-150"
                    value="{{ request('search') }}" onkeyup="this.form.submit()">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
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
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Total (€)</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300">Status</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-300 hidden lg:table-cell">Created At</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-500 dark:text-gray-300 rounded-tr-xl">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($documents as $document)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200 text-gray-900 dark:text-gray-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ ucfirst($document->type) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $document->client->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $document->delivery_days }} days</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">€{{ number_format($document->total, 2, ',', '.') }}</td>
                                    
                                    {{-- Status with dynamic colors --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'estimate' => [
                                                    'draft' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200',
                                                    'sent'  => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200',
                                                ],
                                                'sales_order' => [
                                                    'draft'     => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200',
                                                    'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200',
                                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200',
                                                ],
                                                'sales_invoice' => [
                                                    'waiting_payment' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200',
                                                    'paid'            => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200',
                                                ],
                                            ];
                                            $docType = $document->type;
                                            $docStatus = $document->status;
                                            $badgeClass = $statusColors[$docType][$docStatus] ?? 'bg-gray-100 text-gray-800';
                                        @endphp

                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $docStatus)) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell">{{ $document->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-2">
                                        <a href="{{ route('documents.edit', $document) }}" class="text-amber-sienna hover:text-amber-sienna-dark font-semibold transition duration-150">Edit</a>
                                        <span class="text-gray-400 dark:text-gray-600">|</span>
                                        <a href="{{ route('documents.pdf', $document) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-900 dark:hover:text-purple-300 font-semibold transition duration-150">PDF</a>
                                        <span class="text-gray-400 dark:text-gray-600">|</span>
                                        <button type="button" onclick="openDeleteModal({{ $document->id }}, '{{ addslashes($document->type . ' #' . $document->id) }}')" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold transition duration-150 cursor-pointer" style="background: none !important; border: none !important; outline: none !important; padding: 0 !important; margin: 0 !important; border-radius: 0 !important; box-shadow: none !important;">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if (isset($documents) && method_exists($documents, 'links'))
                    <div class="mt-8">
                        {{ $documents->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 mb-4">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 text-center mb-2">Delete Document</h3>
                <p class="text-gray-600 dark:text-gray-400 text-center mb-6">
                    Are you sure you want to delete <span id="documentName" class="font-semibold text-gray-900 dark:text-gray-100"></span>? This action cannot be undone.
                </p>
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
        function openDeleteModal(documentId, documentName) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('documentName').textContent = documentName;
            document.getElementById('deleteForm').action = `/documents/${documentId}`;
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>
@endsection
