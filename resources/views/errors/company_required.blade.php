<!DOCTYPE html>
<html lang="en">
<head>
    <title>Setup Required</title>
</head>
<body>
    <h1>Setup Required</h1>
    
    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <p>A critical setup step is missing from your account. Please ensure your company information is correctly saved during registration.</p>
    <p>Please try logging in again, or contact support if the problem persists.</p>
    
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
    </a>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
</html>