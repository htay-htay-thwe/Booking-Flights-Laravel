<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    @include('layouts.cdn')
</head>

<body class="flex items-center justify-center min-h-screen bg-center bg-no-repeat bg-cover"> {{-- replace with your background image --}}

    <form action="{{ route('login#action') }}" method="POST"
        class="w-full max-w-sm p-8 shadow-lg bg-gradient-to-b from-indigo-400 to-purple-600 rounded-2xl">
        @csrf

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <div class="flex items-center justify-center w-16 h-16 bg-white rounded-full">
                <img src="/img/logo.png" alt="Logo" class="w-16 h-16 rounded-full"> {{-- Replace with your logo --}}
            </div>
        </div>

        <!-- Title -->
        <h2 class="mb-8 text-2xl font-bold text-center text-white">LOG IN</h2>

        <!-- Email -->
        <div class="mb-5">
            <div class="flex items-center gap-2 text-white">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email"
                    class="w-full text-white placeholder-white bg-transparent border-b border-white focus:outline-none focus:border-white" />
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-5">
            <div class="flex items-center text-white">
                <i class="mr-2 fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password"
                    class="w-full text-white placeholder-white bg-transparent border-b border-white focus:outline-none focus:border-white" />
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Role -->
        <div class="mb-5">
            <div class="flex items-center text-white">
                <i class="mr-2 fas fa-user-tag"></i>
                <input type="text" name="role" id="role" placeholder="Role"
                    class="w-full text-white placeholder-white bg-transparent border-b border-white focus:outline-none focus:border-white" />
            </div>
            @error('role')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Login Button -->
        <button type="submit"
            class="w-full py-2 font-semibold text-indigo-600 transition duration-200 bg-white rounded-full hover:bg-gray-200">
            Login
        </button>

        <!-- Forgot Password -->
        <div class="mt-4 text-center">
            <a href="/forgot-password" class="text-sm text-white hover:underline">Forgot Password?</a>
        </div>

        <!-- Optional: Register Link -->
        <div class="mt-2 text-center">
            <a href="/register" class="text-sm text-white hover:underline">Register Page</a>
        </div>



    </form>
</body>

</html>
