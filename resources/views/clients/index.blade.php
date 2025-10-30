@extends('layouts.app')

@section('title', 'Clients - ' . config('app.name', 'Inventory Management'))

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700">
        <header class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">
                Client Directory
            </h1>
            <a href="{{ route('clients.create') }}"
               class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-xl shadow-lg transition duration-300">
                + Add New Client
            </a>
        </header>

        @if (session('success'))
            <div class="p-4 mb-6 rounded-xl border-l-4 bg-green-50 dark:bg-green-900/20 border-green-500 text-green-700 dark:text-green-300 shadow-md">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 mb-6 rounded-xl border-l-4 bg-red-50 dark:bg-red-900/20 border-red-500 text-red-700 dark:text-red-300 shadow-md">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <div class="relative mb-8">
            <input id="live-search-input" type="text" placeholder="Type to search clients..."
                class="w-full p-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-yellow-600 focus:border-yellow-600 transition duration-150">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-500"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <div class="overflow-hidden rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 relative">
            <div id="loading-overlay" class="absolute inset-0 flex items-center justify-center bg-white/70 dark:bg-gray-800/70 hidden z-10">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-yellow-600"></div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border-collapse">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr class="uppercase text-gray-600 dark:text-gray-300">
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3 text-center">Phone</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="client-list-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($clients as $client)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $client->name }}</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $client->email }}</td>
                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">{{ $client->phone ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="{{ route('clients.edit', $client) }}" class="text-yellow-600 hover:text-yellow-700 font-semibold transition duration-150">Edit</a>
                                    <span class="text-gray-400 dark:text-gray-600">|</span>
                                    <button type="button" onclick="openDeleteModal({{ $client->id }}, '{{ addslashes($client->name) }}')" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold transition duration-150 cursor-pointer" style="background: none !important; border: none !important; outline: none !important; padding: 0 !important; margin: 0 !important; border-radius: 0 !important; box-shadow: none !important;">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="p-6">
            <!-- Red Warning Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 mb-4">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 text-center mb-2">
                Delete Client
            </h3>

            <!-- Message -->
            <p class="text-gray-600 dark:text-gray-400 text-center mb-6">
                Are you sure you want to delete <span id="clientName" class="font-semibold text-gray-900 dark:text-gray-100"></span>? This action cannot be undone.
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
function openDeleteModal(clientId, clientName) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('clientName').textContent = clientName;
    document.getElementById('deleteForm').action = `/clients/${clientId}`;
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

document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('live-search-input');
    const tbody = document.getElementById('client-list-body');
    const loader = document.getElementById('loading-overlay');
    const originalHTML = tbody.innerHTML;
    let timer;

    input.addEventListener('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });

    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(async () => {
            const query = input.value.trim();
            if (!query) {
                loader.classList.add('hidden');
                tbody.innerHTML = originalHTML;
                return;
            }
            loader.classList.remove('hidden');
            try {
                const response = await fetch(`/live-search?query=${encodeURIComponent(query)}&model={{ addslashes(\App\Models\Client::class) }}`);
                const data = await response.json();
                tbody.innerHTML = '';
                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">No clients found.</td></tr>`;
                } else {
                    data.forEach(client => {
                        tbody.innerHTML += `
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">${client.name ?? 'N/A'}</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">${client.email ?? 'N/A'}</td>
                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">${client.phone ?? 'N/A'}</td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="/clients/${client.id}/edit" class="text-yellow-600 hover:text-yellow-700 font-semibold transition duration-150">Edit</a>
                                    <span class="text-gray-400 dark:text-gray-600">|</span>
                                    <button type="button" onclick="openDeleteModal(${client.id}, '${client.name.replace(/'/g, "\\'")}');" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold transition duration-150 cursor-pointer" style="background: none !important; border: none !important; outline: none !important; padding: 0 !important; margin: 0 !important; border-radius: 0 !important; box-shadow: none !important;">
                                        Delete
                                    </button>
                                </td>
                            </tr>`;
                    });
                }
            } catch (error) { console.error('Search error:', error); }
            finally { loader.classList.add('hidden'); }
        }, 300);
    });
});
</script>
@endsection
