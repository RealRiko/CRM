<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Test – Smart Business Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .soft-gradient-bg {
            background: linear-gradient(135deg, #f8f5f2 0%, #f3ede7 100%);
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        .soft-gradient-bg::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -10%;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(202,138,4,0.12), transparent 70%);
            filter: blur(90px);
            animation: float 10s ease-in-out infinite alternate;
        }

        .soft-gradient-bg::after {
            content: '';
            position: absolute;
            bottom: -15%;
            left: -10%;
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, rgba(245,158,11,0.08), transparent 70%);
            filter: blur(100px);
            animation: float 12s ease-in-out infinite alternate-reverse;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            100% { transform: translateY(20px); }
        }

        :root {
            --sienna: #CA8A04;
        }

        .color-sienna {
            color: var(--sienna);
        }
        .bg-sienna {
            background-color: var(--sienna);
        }
        .hover\:bg-sienna-dark:hover {
            background-color: #A06F03;
        }
    </style>
</head>
<body class="soft-gradient-bg flex items-center justify-center p-6">

    <!-- Main Card -->
    <div class="relative z-10 max-w-3xl w-full bg-white/70 backdrop-blur-xl border border-amber-100 shadow-2xl rounded-3xl p-10 sm:p-16 text-center transition duration-300 hover:shadow-amber-200/50">

        <!-- Title -->
        <h1 class="text-5xl sm:text-6xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-amber-700 via-amber-500 to-yellow-400 drop-shadow-sm">
            CHAMANAGE<span class="text-gray-800"> </span>
        </h1>

        <p class="text-gray-600 text-lg sm:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
            Manage clients, inventory, with clarity and confidence.  
            Built for professionals who value design and efficiency.
        </p>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-5">
            <a href="{{ route('login') }}"
               class="px-10 py-4 text-lg font-semibold rounded-xl border border-gray-300 text-gray-700 bg-white/60 hover:bg-white/80 shadow-md transition duration-300 ease-in-out transform hover:scale-[1.02]">
                Log In
            </a>

            <a href="{{ route('register') }}"
               class="px-10 py-4 text-lg font-bold rounded-xl shadow-lg shadow-amber-500/30 text-white bg-sienna hover:bg-sienna-dark transition duration-300 ease-in-out transform hover:scale-[1.03] focus:outline-none focus:ring-4 focus:ring-amber-400/50">
                Get Started →
            </a>
        </div>

        <!-- Footer Text -->
        <p class="text-sm text-gray-500 mt-10">
            Secure. Scalable. Simple.
        </p>
    </div>

</body>
</html>
