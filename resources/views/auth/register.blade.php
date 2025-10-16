<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CRM Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Background */
        .unique-background {
            background-color: #f9f6f3;
            overflow: hidden;
            position: relative;
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

        /* Shadows and card depth */
        .card-shadow {
            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(0, 0, 0, 0.05) inset;
        }

        /* Global Sienna Color */
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

        #message-box {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 50;
            max-width: 90%;
            min-width: 300px;
        }
    </style>
</head>
<body class="unique-background">
    <div id="message-box"></div>

    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
        <div class="max-w-lg w-full bg-white card-shadow p-8 sm:p-10 rounded-3xl transition duration-300 hover:shadow-xl">

            <div class="mb-8 text-center">
                <h2 class="text-4xl font-extrabold mb-1 bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-amber-700">
                    Create Account
                </h2>
                <p class="text-gray-500">Join our professional workspace.</p>
            </div>

            <form id="registrationForm" class="space-y-5" method="POST" action="/register">
                @csrf

                <!-- Name & Surname -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                        <input id="name" type="text" name="name"
                            class="block w-full p-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-800 placeholder-gray-400 shadow-inner
                                   focus:border-sienna focus:ring-2 focus:ring-sienna/30 transition duration-150"
                            required autofocus autocomplete="given-name" placeholder="John">
                    </div>

                    <div>
                        <label for="surname" class="block text-sm font-semibold text-gray-700 mb-2">Surname</label>
                        <input id="surname" type="text" name="surname"
                            class="block w-full p-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-800 placeholder-gray-400 shadow-inner
                                   focus:border-sienna focus:ring-2 focus:ring-sienna/30 transition duration-150"
                            required autocomplete="family-name" placeholder="Doe">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input id="email" type="email" name="email"
                        class="block w-full p-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-800 placeholder-gray-400 shadow-inner
                               focus:border-sienna focus:ring-2 focus:ring-sienna/30 transition duration-150"
                        required autocomplete="username" placeholder="john.doe@example.com">
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input id="password" type="password" name="password"
                            class="block w-full p-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-800 placeholder-gray-400 shadow-inner
                                   focus:border-sienna focus:ring-2 focus:ring-sienna/30 transition duration-150"
                            required autocomplete="new-password">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            class="block w-full p-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-800 placeholder-gray-400 shadow-inner
                                   focus:border-sienna focus:ring-2 focus:ring-sienna/30 transition duration-150"
                            required>
                    </div>
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-sm font-semibold text-gray-700 mb-2">Country</label>
                    <select id="country" name="country"
                        class="block w-full p-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-800 shadow-inner
                               focus:border-sienna focus:ring-2 focus:ring-sienna/30 transition duration-150"
                        required>
                        <option value="" disabled selected class="text-gray-400">Select Country</option>
                        <option value="LV">Latvia</option>
                        <option value="LT">Lithuania</option>
                        <option value="EE">Estonia</option>
                        <option value="DE">Germany</option>
                        <option value="US">United States</option>
                    </select>
                </div>

                <!-- Company -->
                <div>
                    <label for="company_name" class="block text-sm font-semibold text-gray-700 mb-2">Company Name</label>
                    <input id="company_name" type="text" name="company_name"
                        class="block w-full p-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-800 placeholder-gray-400 shadow-inner
                               focus:border-sienna focus:ring-2 focus:ring-sienna/30 transition duration-150"
                        required placeholder="SIA Example Co.">
                </div>

                <div class="flex flex-col space-y-4 pt-6">
                    <!-- Register Button -->
                    <button type="submit" id="registerButton"
                        class="w-full px-4 py-4 text-xl font-bold rounded-xl shadow-lg shadow-amber-600/30 text-white bg-sienna hover:bg-sienna-dark
                               transition duration-300 ease-in-out transform hover:scale-[1.01]
                               focus:outline-none focus:ring-4 focus:ring-sienna/50">
                        Register & Get Started
                    </button>

                    <!-- Login Link -->
                    <p class="text-sm text-center text-gray-500 mt-4">
                        Already have an account?
                        <a href="/login" class="color-sienna hover:text-amber-800 font-bold transition duration-150">
                            Log In
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Message box handler
        function showMessage(text, type = 'info') {
            const box = document.getElementById('message-box');
            const color = type === 'success' ? 'bg-green-500' :
                         (type === 'error' ? 'bg-red-500' : 'bg-blue-500');
            const html = `<div class="p-3 mb-2 rounded-lg shadow-lg text-sm text-white ${color} transition-opacity duration-300">${text}</div>`;
            box.insertAdjacentHTML('afterbegin', html);
            setTimeout(() => {
                const message = box.querySelector('div:first-child');
                if (message) message.remove();
            }, 5000);
        }
    </script>
</body>
</html>
