<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-colors duration-300" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-init="
    if (darkMode) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    $watch('darkMode', (value) => {
        if (value) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    });
">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Inventory Management'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/alpine.js'])
</head>
<body>
    <div class="min-h-screen bg-background">
        
        {{-- FIXED: Removed fixed 'text-white'. Added conditional text color (gray-700 in light, white in dark) and background (white in light, gray-800 in dark) --}}
        <nav class="
            shadow-md 
            bg-white dark:bg-gray-800 
            text-gray-700 dark:text-white 
            transition-colors duration-300
        ">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                <div class="hidden sm:flex space-x-6">
                    @auth
                        {{-- FIXED: Removed 'text-white' from link text. Hover state adjusted to be subtle. --}}
                        <a href="{{ route('dashboard') }}" class="
                            nav-link px-3 py-2 rounded-lg text-sm font-medium 
                            hover:bg-gray-100 dark:hover:bg-gray-700 
                            transition-all duration-300 ease-in-out
                        ">
                            Home
                        </a>
                        <a href="{{ route('products.index') }}" class="nav-link px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 ease-in-out">
                            Products
                        </a>
                        <a href="{{ route('clients.index') }}" class="nav-link px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 ease-in-out">
                            Clients
                        </a>
                        <a href="{{ route('documents.index') }}" class="nav-link px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 ease-in-out">
                            Documents
                        </a>
                        <a href="{{ route('invoices.index') }}" class="nav-link px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 ease-in-out">
                            Invoices
                        </a>
                        <a href="{{ route('workers.index') }}" class="nav-link px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 ease-in-out">
                            Workers
                        </a>
                    @endauth
                </div>

                <div class="flex items-center space-x-4">
                    {{-- FIXED: Added conditional text color for the button itself (since it was outside the general nav text color) --}}
                    <button x-on:click="darkMode = !darkMode" class="
                        button px-3 py-1 rounded-lg text-sm 
                        text-white dark:text-white
                        bg-indigo-600 hover:bg-indigo-700 
                        dark:bg-indigo-600 dark:hover:bg-indigo-700
                    ">
                        <span x-show="!darkMode">Light</span>
                        <span x-show="darkMode">Dark</span>
                    </button>

                    @auth
                        <div class="relative">
                            {{-- FIXED: Removed fixed 'text-white' on the dropdown button --}}
                            <button x-on:click="$refs.dropdown.classList.toggle('hidden')" class="
                                flex items-center focus:outline-none 
                                text-gray-700 dark:text-white 
                                transition-colors duration-300
                            ">
                                {{ Auth::user()->name }}
                                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-ref="dropdown" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 hidden z-10">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-600">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-600">Log Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Login/Register links inherit color from the nav parent --}}
                        <a href="{{ route('login') }}" class="nav-link px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 ease-in-out">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="nav-link px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 ease-in-out">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        @yield('content')
    </div>
</body>
</html>