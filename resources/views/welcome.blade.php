<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Test - Next-Gen Management</title>
    {{-- Using Tailwind CDN for guaranteed styling in the Canvas preview. --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Ensuring a modern, clean font is used */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* --- 2025 Aesthetic: Layered Gradient Background --- */
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
            text-center max-w-lg w-full 
            bg-gray-900/60 p-10 sm:p-14 rounded-3xl 
            shadow-2xl backdrop-blur-xl 
            border border-white/10 ring-2 ring-indigo-500/20 
            transition duration-500 hover:ring-indigo-500/40
        ">
            
            <!-- Logo/Branding Section -->
            <div class="mb-10">
                
                {{-- Title with stronger, wider gradient --}}
                <h1 class="
                    text-7xl font-black leading-tight 
                    bg-clip-text text-transparent 
                    bg-gradient-to-r from-indigo-300 via-white to-indigo-400 
                    drop-shadow-lg
                ">
                    CRM <span class="text-indigo-400">Test</span>
                </h1>
            </div>
            
            <p class="text-xl text-gray-300 mb-12 font-light tracking-wide">
                Seamlessly manage inventory, clients, and documents with next-generation efficiency.
            </p>
            
            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                
                {{-- Secondary Action (Login) - Clean and transparent --}}
                <a href="{{ route('login') }}" class="
                    w-full sm:w-auto px-10 py-3 text-lg font-medium 
                    rounded-xl border border-gray-600 
                    text-gray-300 bg-gray-800/50 hover:bg-gray-700/50 
                    transition duration-300 ease-in-out transform hover:scale-[1.03]
                ">
                    Login
                </a>
                
                {{-- Primary Action (Get Started) - Heroic button with ring glow --}}
                <a href="{{ route('register') }}" class="
                    w-full sm:w-auto px-10 py-3 text-lg font-bold 
                    rounded-xl shadow-xl shadow-indigo-500/50 
                    text-white bg-indigo-600 hover:bg-indigo-700 
                    transition duration-300 ease-in-out transform hover:scale-[1.03]
                    focus:outline-none focus:ring-4 focus:ring-indigo-400 focus:ring-opacity-80
                ">
                    Start Your Free Trial &rarr;
                </a>
            </div>
        </div>
    </div>
</body>
</html>