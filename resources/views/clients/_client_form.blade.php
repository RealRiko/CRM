{{-- 
    This partial contains the complete form logic for both Create and Update clients.
    It expects an optional $client variable to be present. 
    Design Theme: Amber (Consistent with Goals/Products)
--}}
<div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl max-w-4xl mx-auto border border-gray-100 dark:border-gray-700">
            
    {{-- Dynamic Header Text --}}
    <h1 class="text-3xl font-extrabold text-center mb-8 text-amber-600 dark:text-amber-400 border-b border-gray-200 dark:border-gray-700 pb-4">
        {{ isset($client) ? 'Edit Client: ' . ($client->name ?? 'N/A') : 'Create New Client' }}
    </h1>

    {{-- Session Success Alert (Modernized) --}}
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
    
    {{-- Validation Error Alert --}}
    @if ($errors->any())
        <div class="
            p-4 mb-6 rounded-xl border-l-4 
            bg-red-50 dark:bg-red-900/20 
            border-red-500 text-red-700 dark:text-red-300
            shadow-md
        ">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Setup: Dynamic Action and Method --}}
    <form action="{{ isset($client) ? route('clients.update', $client) : route('clients.store') }}" method="POST" class="space-y-6">
        @csrf
        {{-- Conditional PATCH method for updates --}}
        @if (isset($client))
            @method('PATCH')
        @endif

        {{-- Name Field --}}
        <div>
            <label for="name" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" 
                class="
                    w-full p-3 border border-gray-300 dark:border-gray-600 
                    rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                    focus:ring-amber-500 focus:border-amber-500 transition duration-150
                    @error('name') border-red-500 @enderror
                " 
                value="{{ old('name', $client->name ?? '') }}" 
                required>
            @error('name')
                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email Field --}}
        <div>
            <label for="email" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email" 
                class="
                    w-full p-3 border border-gray-300 dark:border-gray-600 
                    rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                    focus:ring-amber-500 focus:border-amber-500 transition duration-150
                    @error('email') border-red-500 @enderror
                " 
                value="{{ old('email', $client->email ?? '') }}" 
                required>
            @error('email')
                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Phone Field --}}
        <div>
            <label for="phone" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone</label>
            <input type="text" name="phone" id="phone" 
                class="
                    w-full p-3 border border-gray-300 dark:border-gray-600 
                    rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                    focus:ring-amber-500 focus:border-amber-500 transition duration-150
                    @error('phone') border-red-500 @enderror
                " 
                value="{{ old('phone', $client->phone ?? '') }}">
            @error('phone')
                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Address Field (Added for completeness in a business app) --}}
        <div>
            <label for="address" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Address</label>
            <input type="text" name="address" id="address" 
                class="
                    w-full p-3 border border-gray-300 dark:border-gray-600 
                    rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                    focus:ring-amber-500 focus:border-amber-500 transition duration-150
                    @error('address') border-red-500 @enderror
                " 
                value="{{ old('address', $client->address ?? '') }}">
            @error('address')
                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row justify-between items-center pt-4 space-y-4 sm:space-y-0 sm:space-x-4">
            
            <a href="{{ route('clients.index') }}" class="
                w-full sm:w-auto px-8 py-3 
                text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600
                bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600
                font-bold rounded-xl shadow-md 
                transition duration-300 ease-in-out transform hover:scale-[1.01]
                text-center
            ">
                &larr; Back to List
            </a>

            {{-- Primary Button (Dynamic Text, Amber Theme) --}}
            <button type="submit" class="
                w-full sm:w-auto px-8 py-3 
                bg-amber-600 hover:bg-amber-700 text-white font-bold 
                rounded-xl shadow-lg 
                transition duration-300 ease-in-out transform hover:scale-[1.01]
                focus:outline-none focus:ring-4 focus:ring-amber-500 focus:ring-opacity-50
            ">
                {{ isset($client) ? 'Update Client' : 'Create Client' }}
            </button>
        </div>
    </form>
</div>