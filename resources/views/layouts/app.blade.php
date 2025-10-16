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
{{-- ISSUE RESOLVED: Removed 'resources/js/alpine.js' as Alpine is already imported and started in app.js --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- === CUSTOM AMBER COLOR STYLES === --}}
<style>
    /* Sienna Amber: #CA8A04 */
    .text-amber-sienna { color: #CA8A04; }
    .border-amber-sienna { border-color: #CA8A04; }
    .bg-amber-sienna { background-color: #CA8A04; }
    .hover\:bg-amber-sienna:hover { background-color: #A16207; } /* A slightly darker shade for hover */
</style>

</head>
<body>
<div class="min-h-screen bg-background">

    <nav class="
        shadow-md 
        bg-white dark:bg-gray-800 
        text-gray-700 dark:text-white 
        transition-colors duration-300
    ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <div class="hidden sm:flex space-x-6">
                @auth
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
                    @if (method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
                        {{-- Assuming you want the admin link to stand out with the primary color --}}
                        <a href="{{ route('dashboard.goal') }}"
                            class="px-3 py-2 text-sm font-medium text-amber-sienna hover:text-amber-sienna/80">
                            Admin 
                        </a>
                    @endif
                @endauth
            </div>

            <div class="flex items-center space-x-4">
                {{-- === AMBER DARK MODE BUTTON CHANGE === --}}
                <button x-on:click="darkMode = !darkMode" class="
                    button px-3 py-1 rounded-lg text-sm 
                    text-white 
                    bg-amber-sienna hover:bg-amber-sienna 
                    dark:bg-amber-sienna dark:hover:bg-amber-sienna
                ">
                    <span x-show="!darkMode">Light</span>
                    <span x-show="darkMode">Dark</span>
                </button>

                @auth
                    <div class="relative">
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