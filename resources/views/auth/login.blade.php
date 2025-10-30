<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRM Test</title>
    {{-- Using Tailwind CDN for guaranteed styling in the Canvas preview. --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .unique-background {
            background-color: #f9f6f3; 
            overflow: hidden;
            position: relative;
        }

        .card-shadow {
            box-shadow: 
                0 20px 50px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(0, 0, 0, 0.05) inset; 
        }
        
:root {
    --sienna: #CA8A04;
}

.color-sienna {
    color: var(--sienna);
}

.bg-sienna {
    background-color: var(--sienna) !important;
}

.hover\:bg-sienna-dark:hover {
    background-color: #A06F03 !important;
}

.focus\:ring-sienna:focus {
    --tw-ring-color: #CA8A04 !important;
}


        .unique-background::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16px;
            background-color: #f3eee8;
            z-index: 0;
        }
    </style>
</head>
<body class="unique-background">
    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
        {{-- Main Centered Card - Clean, Elevated Design --}}
   <div class="
    max-w-md w-full 
    bg-white card-shadow p-10 sm:p-12 rounded-3xl 
    transition duration-300 hover:shadow-2xl
">


            <div class="mb-8 text-center">
                {{-- Text Gradient for unique title --}}
                <h2 class="
                    text-4xl font-extrabold mb-1
                    bg-clip-text text-transparent 
                    bg-gradient-to-r from-gray-800 to-amber-700
                ">
                    Welcome Back
                </h2>
                <p class="text-gray-500">Access your professional dashboard.</p>
            </div>
            
            {{-- Session Status --}}
            @if (session('status'))
                <div class="p-3 mb-4 text-sm text-amber-800 bg-amber-100 rounded-xl border border-amber-200" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input id="email" 
                        class="
                            block w-full p-4 rounded-xl border border-gray-300
                            bg-gray-50 text-gray-800 placeholder-gray-400 shadow-inner
                            focus:border-sienna focus:ring-2 focus:ring-sienna/30 transition duration-150
                            @error('email') ring-2 ring-red-500 border-red-500 @enderror
                        " 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required autofocus 
                        autocomplete="username" 
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>

                    <input id="password" 
                        class="
                            block w-full p-4 rounded-xl border border-gray-300
                            bg-gray-50 text-gray-800 placeholder-gray-400 shadow-inner
                            focus:border-sienna focus:ring-2 focus:ring-sienna/30 transition duration-150
                            @error('password') ring-2 ring-red-500 border-red-500 @enderror
                        "
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password" 
                    />

                    @error('password')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between items-center pt-2">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" 
                            class="
                                h-5 w-5 rounded-md border-gray-300 color-sienna bg-white shadow-sm 
                                focus:ring-sienna focus:ring-offset-2 focus:ring-offset-white
                                transition duration-150
                            " 
                            name="remember"
                        >
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a class="
                            text-sm color-sienna hover:text-amber-800 font-medium 
                            transition duration-150
                        " href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <div class="flex flex-col space-y-4 pt-6">
                    
                    {{-- Log In Button (PRIMARY ACTION: Solid Sienna background applied here) --}}
                    <button type="submit" class="
                        w-full px-4 py-4 text-xl font-bold 
                        rounded-xl shadow-lg shadow-amber-600/30 
                        text-white bg-sienna hover:bg-sienna-dark 
                        transition duration-300 ease-in-out transform hover:scale-[1.01]
                        focus:outline-none focus:ring-4 focus:ring-sienna/50
                        flex items-center justify-center
                    ">
                        {{ __('Sign In') }}
                    </button>

                    {{-- Sign Up Link --}}
                    <p class="text-sm text-center text-gray-500 mt-4">
                        New user? 
                        <a href="{{ route('register') }}" class="color-sienna hover:text-amber-800 font-bold transition duration-150">
                            Create an Account
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>