@extends('layouts.app')

@section('title', 'Set Monthly Goal')

@section('content')
<div class="max-w-xl mx-auto p-4 sm:p-8 mt-10 animate-slide-up">
    
    {{-- Goal Card --}}
    <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700">
        
        <header class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                <span class="text-sienna mr-3 text-4xl"></span>
                Set Monthly Revenue Goal
            </h1>
        </header>

        {{-- Session Messages --}}
        @if (session('success'))
            <div class="p-4 mb-6 rounded-xl border-l-4 border-green-500 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 shadow-md">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 mb-6 rounded-xl border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 shadow-md">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.setGoal') }}">
            @csrf
            
            {{-- Goal Input Field --}}
            <div class="mb-6">
                <label for="monthly_goal" class="block text-gray-700 dark:text-gray-300 font-medium mb-2 text-lg">
                    Current Goal for {{ date('F Y') }} (€)
                </label>
                
                <div class="relative">
                    <input type="number" step="0.01" min="0" name="monthly_goal" id="monthly_goal"
                        value="{{ old('monthly_goal', $company->monthly_goal ?? 0) }}"
                        class="
                            w-full border-gray-300 dark:border-gray-600 
                            rounded-xl px-5 py-3 text-xl 
                            shadow-inner dark:bg-gray-700 dark:text-gray-100
                            focus:ring-sienna focus:border-sienna transition duration-150
                            pl-10
                        "
                        placeholder="e.g., 50000.00">
                    
                    {{-- Euro Icon --}}
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 text-xl font-medium">
                        €
                    </span>
                </div>
                
                @error('monthly_goal')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Save Button (Using Sienna Theme) --}}
            <div class="flex justify-end pt-4">
                <button type="submit" class="
                    bg-sienna hover:bg-sienna-dark text-white font-bold 
                    px-8 py-3 rounded-xl shadow-lg 
                    transition duration-300 ease-in-out transform hover:scale-[1.02]
                    focus:outline-none focus:ring-4 focus:ring-sienna focus:ring-opacity-40
                ">
                    Update Goal
                </button>
            </div>
            
        </form>
    </div>
    
</div>
@endsection