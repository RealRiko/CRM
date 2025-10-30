<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="transition-colors duration-300"
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' 
                || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                mobileMenu: false }"
      x-init="
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Amber theme --}}
    <style>
        .text-amber-sienna { color: #CA8A04; }
        .border-amber-sienna { border-color: #CA8A04; }
        .bg-amber-sienna { background-color: #CA8A04; }
        .hover\:bg-amber-sienna:hover { background-color: #A16207; }
    </style>
</head>

<body class="min-h-screen bg-background">

<!-- Navbar -->
<nav class="shadow-md bg-white dark:bg-gray-800 text-gray-700 dark:text-white transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <!-- Logo / Brand -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('dashboard') }}" class="font-bold text-xl text-amber-sienna dark:text-amber-400">Chamanage</a>
        </div>

        <!-- Desktop Links -->
        <div class="hidden sm:flex space-x-6">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Home</a>
                <a href="{{ route('products.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Products</a>
                <a href="{{ route('inventory.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Storage</a>
                <a href="{{ route('clients.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Clients</a>
                <a href="{{ route('documents.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Documents</a>
                <a href="{{ route('invoices.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Invoices</a>
                <a href="{{ route('workers.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Workers</a>

                @if (method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
                    <a href="{{ route('admin.companySettings') }}"
                       class="px-3 py-2 rounded-lg font-medium text-amber-sienna hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-amber-sienna/80">Admin</a>
                @endif
            @endauth
        </div>

        <!-- Right: Dark Mode + User / Auth -->
        <div class="flex items-center space-x-4">
            <!-- Dark Mode Toggle -->
            <button x-on:click="darkMode = !darkMode"
                    class="px-3 py-1 rounded-lg text-sm text-white bg-amber-sienna hover:bg-amber-sienna-dark">
                <span x-show="!darkMode">Light</span>
                <span x-show="darkMode">Dark</span>
            </button>

            @auth
                <!-- User Dropdown -->
                <div class="relative">
                    <button x-on:click="$refs.dropdown.classList.toggle('hidden')"
                            class="flex items-center focus:outline-none text-white transition-colors duration-300">
                        {{ Auth::user()->name }}
                        <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-ref="dropdown"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 hidden z-10">
                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-600">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-600">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Login</a>
                <a href="{{ route('register') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Register</a>
            @endauth

            <!-- Hamburger -->
            <button @click="mobileMenu = !mobileMenu" class="sm:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                <svg x-show="!mobileMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 text-gray-700 dark:text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 text-gray-700 dark:text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenu" class="sm:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 transition-all">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @auth
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Home</a>
                <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Products</a>
                <a href="{{ route('inventory.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Storage</a>
                <a href="{{ route('clients.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Clients</a>
                <a href="{{ route('documents.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Documents</a>
                <a href="{{ route('invoices.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Invoices</a>
                <a href="{{ route('workers.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Workers</a>
                @if (method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
                    <a href="{{ route('admin.companySettings') }}" class="block px-3 py-2 rounded-lg text-amber-sienna hover:bg-gray-100 dark:hover:bg-gray-700">Admin</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Log Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Login</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Register</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Main Content -->
<main>
    @yield('content')
</main>

@stack('scripts')
</body>
</html>
