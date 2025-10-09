@extends('layouts.app')

@section('title', 'Create Worker - ' . config('app.name', 'Inventory Management'))

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        {{-- Form Container Card --}}
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl max-w-4xl mx-auto border border-gray-100 dark:border-gray-700">
            
            <h1 class="text-3xl font-extrabold text-center mb-8 text-indigo-600 dark:text-indigo-400 border-b border-gray-200 dark:border-gray-700 pb-4">
                 Create New Worker
            </h1>

            {{-- General Success/Error Session Messages --}}
            @if (session('success'))
                <div class="p-4 mb-6 rounded-xl border-l-4 bg-green-50 dark:bg-green-900/20 border-green-500 text-green-700 dark:text-green-300 shadow-md">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 mb-6 rounded-xl border-l-4 bg-red-50 dark:bg-red-900/20 border-red-500 text-red-700 dark:text-red-300 shadow-md">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation Error Alert (Modernized) --}}
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

            {{-- Form Setup --}}
            <form action="{{ route('workers.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Worker Details Grid (2 columns on medium screens) --}}
                <div class="grid sm:grid-cols-2 gap-6">
                    
                    {{-- Name Field --}}
                    <div>
                        <label for="name" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" 
                            class="
                                w-full p-3 border border-gray-300 dark:border-gray-600 
                                rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                                focus:ring-indigo-500 focus:border-indigo-500 transition duration-150
                                @error('name') border-red-500 @enderror
                            " 
                            value="{{ old('name') }}" 
                            required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Surname Field --}}
                    <div>
                        <label for="surname" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Surname <span class="text-red-500">*</span></label>
                        <input type="text" name="surname" id="surname" 
                            class="
                                w-full p-3 border border-gray-300 dark:border-gray-600 
                                rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                                focus:ring-indigo-500 focus:border-indigo-500 transition duration-150
                                @error('surname') border-red-500 @enderror
                            " 
                            value="{{ old('surname') }}" 
                            required>
                        @error('surname')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Email Field --}}
                <div>
                    <label for="email" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" 
                        class="
                            w-full p-3 border border-gray-300 dark:border-gray-600 
                            rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                            focus:ring-indigo-500 focus:border-indigo-500 transition duration-150
                            @error('email') border-red-500 @enderror
                        " 
                        value="{{ old('email') }}" 
                        required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Fields Grid (2 columns on medium screens) --}}
                <div class="grid sm:grid-cols-2 gap-6">
                    {{-- Password Field --}}
                    <div>
                        <label for="password" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" 
                            class="
                                w-full p-3 border border-gray-300 dark:border-gray-600 
                                rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                                focus:ring-indigo-500 focus:border-indigo-500 transition duration-150
                                @error('password') border-red-500 @enderror
                            "
                            required>
                        @error('password')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password Field --}}
                    <div>
                        <label for="password_confirmation" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                            class="
                                w-full p-3 border border-gray-300 dark:border-gray-600 
                                rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                                focus:ring-indigo-500 focus:border-indigo-500 transition duration-150
                                @error('password') border-red-500 @enderror
                            "
                            required>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row justify-between items-center pt-4 space-y-4 sm:space-y-0 sm:space-x-4">
                    
                    {{-- Back Button --}}
                    <a href="{{ route('workers.index') }}" class="
                        w-full sm:w-auto px-8 py-3 
                        text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600
                        bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600
                        font-bold rounded-xl shadow-md 
                        transition duration-300 ease-in-out transform hover:scale-[1.01]
                        text-center
                    ">
                        &larr; Back to List
                    </a>

                    {{-- Submit Button --}}
                    <button type="submit" class="
                        w-full sm:w-auto px-8 py-3 
                        bg-indigo-600 hover:bg-indigo-700 text-white font-bold 
                        rounded-xl shadow-lg 
                        transition duration-300 ease-in-out transform hover:scale-[1.01]
                        focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50
                    ">
                         Create Worker
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection