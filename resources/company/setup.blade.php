@extends('layouts.app')

@section('title', 'Company Setup Required')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Centered, stylish card --}}
        <div class="bg-white dark:bg-gray-800 p-8 sm:p-10 rounded-2xl shadow-2xl max-w-xl mx-auto border border-indigo-200 dark:border-indigo-900">
            
            <div class="text-center">
                {{-- Icon for visual flair --}}
                <svg class="mx-auto h-16 w-16 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>

                <h1 class="mt-4 text-4xl font-extrabold text-indigo-600 dark:text-indigo-400">
                    Company Setup Required
                </h1>

                <p class="mt-4 text-lg text-gray-700 dark:text-gray-300">
                    It looks like you need to set up your company details before you can proceed with inventory management.
                </p>
            </div>

            {{-- Error Message (Styled Alert) --}}
            @if (session('error'))
                <div class="
                    p-4 mt-6 rounded-xl border-l-4 
                    bg-red-50 dark:bg-red-900/20 
                    border-red-500 text-red-700 dark:text-red-300
                    shadow-md
                ">
                    <p class="font-medium">⚠️ Error: {{ session('error') }}</p>
                </div>
            @endif

            <div class="mt-8">
                {{-- Primary Call to Action (Placeholder for Setup Link) --}}
                <a href="{{ route('setup.form') }}" class="
                    w-full flex justify-center py-3 px-6 
                    border border-transparent text-lg font-bold rounded-xl shadow-lg 
                    text-white bg-indigo-600 hover:bg-indigo-700 
                    transition duration-300 ease-in-out transform hover:scale-[1.01]
                    focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50
                ">
                    Go to Setup Form
                </a>

                {{-- Secondary Action --}}
                <a href="{{ route('dashboard') }}" class="
                    mt-4 w-full flex justify-center py-3 px-6 
                    border border-gray-300 dark:border-gray-600 text-sm font-semibold 
                    rounded-xl shadow-sm text-gray-700 dark:text-gray-300 
                    bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600
                    transition duration-300 ease-in-out
                ">
                    &larr; Return to Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection