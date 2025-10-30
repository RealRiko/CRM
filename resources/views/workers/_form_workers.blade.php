@php
    $isEdit = isset($worker);
@endphp

<form action="{{ $isEdit ? route('workers.update', $worker) : route('workers.store') }}" method="POST" class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PATCH')
    @endif

    <div class="grid sm:grid-cols-2 gap-6">
        {{-- Name --}}
        <div>
            <label for="name" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name"
                value="{{ old('name', $worker->name ?? '') }}"
                class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('name') border-red-500 @enderror"
                required>
            @error('name')<p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
        </div>

        {{-- Surname --}}
        <div>
            <label for="surname" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Surname <span class="text-red-500">*</span></label>
            <input type="text" name="surname" id="surname"
                value="{{ old('surname', $worker->surname ?? '') }}"
                class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('surname') border-red-500 @enderror"
                required>
            @error('surname')<p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" id="email"
            value="{{ old('email', $worker->email ?? '') }}"
            class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('email') border-red-500 @enderror"
            required>
        @error('email')<p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
    </div>

    {{-- Password Fields --}}
    <div class="grid sm:grid-cols-2 gap-6">
        <div>
            <label for="password" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ $isEdit ? 'New Password' : 'Password' }} {{ $isEdit ? '' : '*' }}</label>
            <input type="password" name="password" id="password"
                placeholder="{{ $isEdit ? 'Leave blank to keep current password' : '' }}"
                class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('password') border-red-500 @enderror"
                {{ $isEdit ? '' : 'required' }}>
            @error('password')<p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ $isEdit ? 'Confirm Password' : 'Confirm Password' }} {{ $isEdit ? '' : '*' }}</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                placeholder="{{ $isEdit ? 'Leave blank to keep current password' : '' }}"
                class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100 focus:ring-amber-500 focus:border-amber-500 transition duration-150"
                {{ $isEdit ? '' : 'required' }}>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row justify-between items-center pt-4 space-y-4 sm:space-y-0 sm:space-x-4">
        <a href="{{ route('workers.index') }}" class="w-full sm:w-auto px-8 py-3 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 font-bold rounded-xl shadow-md transition duration-300 ease-in-out transform hover:scale-[1.01] text-center">
            &larr; Back to List
        </a>
        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg transition duration-300 ease-in-out transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-amber-500 focus:ring-opacity-50">
            {{ $isEdit ? 'Update Worker' : 'Create Worker' }}
        </button>
    </div>
</form>
