@extends('layouts.app')

@section('title', 'Create Client - ' . config('app.name', 'Inventory Management'))

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        {{-- Form Container Card --}}
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl max-w-4xl mx-auto border border-gray-100 dark:border-gray-700">
            
            <h1 class="text-3xl font-extrabold text-center mb-8 text-indigo-600 dark:text-indigo-400 border-b border-gray-200 dark:border-gray-700 pb-4">
                ➕ Create New Client
            </h1>

            {{-- Error Alert (Modernized) --}}
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
            <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
                @csrf

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

                {{-- Phone Field --}}
                <div>
                    <label for="phone" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <input type="text" name="phone" id="phone" 
                        class="
                            w-full p-3 border border-gray-300 dark:border-gray-600 
                            rounded-xl shadow-inner dark:bg-gray-700 dark:text-gray-100
                            focus:ring-indigo-500 focus:border-indigo-500 transition duration-150
                            @error('phone') border-red-500 @enderror
                        " 
                        value="{{ old('phone') }}">
                    @error('phone')
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

                    <button type="submit" class="
                        w-full sm:w-auto px-8 py-3 
                        bg-indigo-600 hover:bg-indigo-700 text-white font-bold 
                        rounded-xl shadow-lg 
                        transition duration-300 ease-in-out transform hover:scale-[1.01]
                        focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50
                    ">
                        ✨ Create Client
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection