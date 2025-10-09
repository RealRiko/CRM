<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRM Test</title>
    {{-- Using Tailwind CDN for guaranteed styling in the Canvas preview. --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Ensuring a modern, clean font is used */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* --- 2025 Aesthetic: Layered Gradient Background (Copied from welcome.blade.php) --- */
        .neon-background {
            background-color: #0c0d12; /* Deep dark base */
            overflow: hidden;
            position: relative;
        }
        
        /* Neon effect 1 (Top Left - Indigo) */
        .neon-background::before {
            content: '';
            position: absolute;
            top: -10%;
            left: -10%;
            width: 50vw;
            height: 50vw;
            background: rgba(49, 46, 129, 0.4); /* indigo-700/40 */
            filter: blur(200px);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            animation: move-gradient-1 15s infinite alternate ease-in-out;
        }

        /* Neon effect 2 (Bottom Right - Purple/Pink) */
        .neon-background::after {
            content: '';
            position: absolute;
            bottom: -10%;
            right: -10%;
            width: 40vw;
            height: 40vw;
            background: rgba(168, 85, 247, 0.3); /* violet-500/30 */
            filter: blur(200px);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            animation: move-gradient-2 20s infinite alternate-reverse ease-in-out;
        }

        @keyframes move-gradient-1 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(20vw, 20vh) scale(1.1); }
        }

        @keyframes move-gradient-2 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-15vw, -15vh) scale(1.2); }
        }
    </style>
</head>
<body class="neon-background">
    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
        {{-- Main Centered Card - Glassmorphism Effect --}}
        <div class="
            max-w-md w-full 
            bg-gray-900/60 p-8 sm:p-10 rounded-3xl 
            shadow-2xl backdrop-blur-xl 
            border border-white/10 ring-2 ring-indigo-500/20 
            transition duration-500 hover:ring-indigo-500/40
        ">

            <div class="mb-8 text-center">
                <h2 class="
                    text-4xl font-black mb-1
                    bg-clip-text text-transparent 
                    bg-gradient-to-r from-indigo-300 via-white to-indigo-400 
                    drop-shadow-lg
                ">
                    Sign In
                </h2>
                <p class="text-gray-400">Access your next-gen workspace.</p>
            </div>
            
            {{-- Session Status --}}
            @if (session('status'))
                <div class="p-3 mb-4 text-sm text-green-300 bg-green-900/50 rounded-lg" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input id="email" 
                        class="
                            block w-full p-3 rounded-lg border-none
                            bg-gray-800/80 text-white placeholder-gray-500 shadow-md
                            focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-150
                            @error('email') ring-2 ring-red-500 @enderror
                        " 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required autofocus 
                        autocomplete="username" 
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>

                    <input id="password" 
                        class="
                            block w-full p-3 rounded-lg border-none
                            bg-gray-800/80 text-white placeholder-gray-500 shadow-md
                            focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-150
                            @error('password') ring-2 ring-red-500 @enderror
                        "
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password" 
                    />

                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex justify-between items-center pt-2">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" 
                            class="
                                h-5 w-5 rounded border-gray-600 bg-gray-700/80 text-indigo-500 shadow-sm 
                                focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-900 
                                transition duration-150
                            " 
                            name="remember"
                        >
                        <span class="ms-3 text-sm text-gray-300">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex flex-col space-y-4 pt-4">
                    
                    {{-- Log In Button (Primary Heroic Style) --}}
                    <button type="submit" class="
                        w-full px-4 py-3 text-lg font-bold 
                        rounded-xl shadow-xl shadow-indigo-500/50 
                        text-white bg-indigo-600 hover:bg-indigo-700 
                        transition duration-300 ease-in-out transform hover:scale-[1.01]
                        focus:outline-none focus:ring-4 focus:ring-indigo-400 focus:ring-opacity-80
                    ">
                        {{ __('Log in') }} &rarr;
                    </button>

                    {{-- Forgot Password Link --}}
                    @if (Route::has('password.request'))
                        <a class="
                            text-sm text-indigo-400 hover:text-indigo-300 font-medium 
                            transition duration-150 text-center
                        " href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</body>
</html>