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

        .neon-background {
            background-color: #0c0d12;
            overflow: hidden;
            position: relative;
        }
        
        .neon-background::before {
            content: '';
            position: absolute;
            top: -10%;
            left: -10%;
            width: 50vw;
            height: 50vw;
            background: rgba(49, 46, 129, 0.4);
            filter: blur(200px);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            animation: move-gradient-1 15s infinite alternate ease-in-out;
        }

        .neon-background::after {
            content: '';
            position: absolute;
            bottom: -10%;
            right: -10%;
            width: 40vw;
            height: 40vw;
            background: rgba(168, 85, 247, 0.3);
            filter: blur(200px);
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
        
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='white'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem !important;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Message box styling */
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
<body class="neon-background">
    <div id="message-box"></div>

    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
        <div class="
            max-w-lg w-full 
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
                    Create Account
                </h2>
                <p class="text-gray-400">Join the next-gen workspace.</p>
            </div>
            
            <form id="registrationForm" class="space-y-5" method="POST" action="/register">
                @csrf 

                <!-- Name & Surname -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Name</label>
                        <input id="name" type="text" name="name" 
                            class="block w-full p-3 rounded-lg border-none bg-gray-800/80 text-white placeholder-gray-500 shadow-md focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-150" 
                            required autofocus autocomplete="given-name" placeholder="John">
                    </div>

                    <div>
                        <label for="surname" class="block text-sm font-medium text-gray-300 mb-1">Surname</label>
                        <input id="surname" type="text" name="surname" 
                            class="block w-full p-3 rounded-lg border-none bg-gray-800/80 text-white placeholder-gray-500 shadow-md focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-150" 
                            required autocomplete="family-name" placeholder="Doe">
                    </div>
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input id="email" type="email" name="email" 
                        class="block w-full p-3 rounded-lg border-none bg-gray-800/80 text-white placeholder-gray-500 shadow-md focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-150" 
                        required autocomplete="username" placeholder="john.doe@example.com">
                </div>

                <!-- Password & Confirm Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                        <input id="password" type="password" name="password" 
                            class="block w-full p-3 rounded-lg border-none bg-gray-800/80 text-white placeholder-gray-500 shadow-md focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-150" 
                            required autocomplete="new-password">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" 
                            class="block w-full p-3 rounded-lg border-none bg-gray-800/80 text-white placeholder-gray-500 shadow-md focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-150" 
                            required>
                    </div>
                </div>
                
                <!-- Country Select -->
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-300 mb-1">Country</label>
                    <select id="country" name="country" 
                        class="block w-full p-3 rounded-lg border-none bg-gray-800/80 text-white placeholder-gray-500 shadow-md focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-150" 
                        required>
                        <option value="" disabled selected class="text-gray-500">Select Country</option>
                        <option value="LV">Latvia</option>
                        <option value="LT">Lithuania</option>
                        <option value="EE">Estonia</option>
                        <option value="DE">Germany</option>
                        <option value="US">United States</option>
                    </select>
                </div>
                
                <!-- Company Name -->
                <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-300 mb-1">Company Name</label>
                    <input id="company_name" type="text" name="company_name" 
                        class="block w-full p-3 rounded-lg border-none bg-gray-800/80 text-white placeholder-gray-500 shadow-md focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-150" 
                        required placeholder="SIA Example Co.">
                </div>

                <div class="flex flex-col space-y-4 pt-4">
                    
                    <!-- Register Button -->
                    <button type="submit" id="registerButton" class="
                        w-full px-4 py-3 text-lg font-bold 
                        rounded-xl shadow-xl shadow-indigo-500/50 
                        text-white bg-indigo-600 hover:bg-indigo-700 
                        transition duration-300 ease-in-out transform hover:scale-[1.01]
                        focus:outline-none focus:ring-4 focus:ring-indigo-400 focus:ring-opacity-80
                    ">
                        Register & Get Started
                    </button>

                    <!-- Login Link -->
                    <a class="
                        text-sm text-indigo-400 hover:text-indigo-300 font-medium 
                        transition duration-150 text-center
                    " href="/login">
                        Already registered? Log In
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Function to display messages (since alert() is banned)
        function showMessage(text, type = 'info') {
            const box = document.getElementById('message-box');
            
            const color = type === 'success' ? 'bg-green-500' : (type === 'error' ? 'bg-red-500' : 'bg-blue-500');
            const html = `
                <div class="p-3 mb-2 rounded-lg shadow-lg text-sm text-white ${color} transition-opacity duration-300">
                    ${text}
                </div>
            `;
            box.insertAdjacentHTML('afterbegin', html);
            
            // Auto-hide messages
            setTimeout(() => {
                const message = box.querySelector('div:first-child');
                if (message) message.remove();
            }, 5000);
        }

        async function handleRegistration(event) {
            event.preventDefault();

            const button = document.getElementById('registerButton');
            button.disabled = true;
            button.textContent = 'Processing...';

            const form = event.target;
            
            if (!form.checkValidity()) {
                showMessage("Please fill out all required fields.", 'info');
                button.textContent = 'Register & Get Started';
                button.disabled = false;
                return;
            }
            
            // Get the CSRF token reliably
            const csrfTokenInput = form.querySelector('input[name="_token"]');
            const csrfToken = csrfTokenInput ? csrfTokenInput.value : '';
            
            if (!csrfToken) {
                 showMessage("Security token missing. Please refresh the page.", 'error');
                 button.textContent = 'Register & Get Started';
                 button.disabled = false;
                 return;
            }
            
            // Collect all form data
            const data = {
                name: form.name.value, 
                surname: form.surname.value,
                email: form.email.value,
                password: form.password.value,
                password_confirmation: form.password_confirmation.value,
                country: form.country.value,
                company_name: form.company_name.value,
            };

            // Basic password matching check 
            if (data.password !== data.password_confirmation) {
                showMessage("Passwords do not match.", 'error');
                button.textContent = 'Register & Get Started';
                button.disabled = false;
                return;
            }

            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken, 
                        'Accept': 'application/json', // Requesting JSON response
                    },
                    body: JSON.stringify(data)
                });
                
                let errorMessage = '';

                // Check for successful status (200-299 range)
                if (response.ok) {
                    const result = await response.json();
                    
                    showMessage('Registration successful! Redirecting...', 'success');
                    
                    setTimeout(() => {
                        window.location.href = result.redirect || '/dashboard'; 
                    }, 1000);

                } else {
                    // --- CRITICAL FIX FOR SYNTAX ERROR ---
                    const contentType = response.headers.get("content-type");
                    
                    // 1. Check if the response is actually JSON
                    if (contentType && contentType.includes("application/json")) {
                        const errorData = await response.json();
                        
                        // Handle Laravel Validation errors (status 422)
                        if (errorData.errors) {
                            const firstErrorKey = Object.keys(errorData.errors)[0];
                            errorMessage = errorData.errors[firstErrorKey][0];
                        } else if (errorData.message) {
                            errorMessage = errorData.message;
                        }

                    } else {
                        // 2. Response is NOT JSON (it's HTML) -> This is the source of the SyntaxError
                        const errorText = await response.text();
                        console.error(`Non-JSON Response Received (Status ${response.status}):`, errorText);

                        if (response.status === 419) {
                            errorMessage = "Page expired (Security Token mismatch). Please refresh and try again.";
                        } else if (response.status === 500) {
                            errorMessage = "A Server Error (500) occurred. Check your PHP logs for details.";
                        } else if (response.status >= 300 && response.status < 400) {
                            // This usually means a redirect to a non-JSON page
                            errorMessage = `Server attempted a redirect (Status ${response.status}). Check if the backend is configured to return JSON for this route.`;
                        } else {
                             // This handles the generic HTML response that causes the SyntaxError
                            errorMessage = `Unexpected server response format (Status ${response.status}). The server returned an HTML page instead of JSON.`;
                        }
                    }

                    showMessage(errorMessage, 'error');
                }

            } catch (error) {
                console.error('Network error during registration:', error);
                showMessage('Network error or invalid server connection.', 'error');
            } finally {
                button.textContent = 'Register & Get Started';
                button.disabled = false;
            }
        }

        // Attach event listener to the form's submit event
        document.getElementById('registrationForm').addEventListener('submit', handleRegistration);
    </script>
</body>
</html>