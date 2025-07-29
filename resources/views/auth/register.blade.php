<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    @include('layouts.cdn')

</head>

<body class="flex items-center justify-center min-h-screen bg-center bg-no-repeat bg-cover"
    style="background-image: url('/images/city-bg.jpg');"> {{-- change to your background --}}

    <form action="{{ route('register#action') }}" method="POST"
        class="w-full max-w-sm p-8 shadow-lg bg-gradient-to-b from-indigo-400 to-purple-600 rounded-2xl">
        @csrf

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <div class="flex items-center justify-center w-16 h-16 bg-white rounded-full">
                <img src="/img/logo.png" alt="Logo" class="w-16 h-16 rounded-full"> {{-- Replace with your logo --}}
            </div>
        </div>

        <h2 class="mb-8 text-2xl font-bold text-center text-white">Create Account</h2>

        @if (session('error'))
            <div class="space-y-4 text-sm text-red-500">{{ session('error') }}</div>
        @endif
        <div class="space-y-4">
            <!-- Username -->
            <div class="mb-5">
                <div class="flex items-center text-white">
                    <i class="mr-2 fas fa-user"></i>
                    <input type="text" name="username" id="username" @error('username') is-invalid @enderror
                        class="w-full text-white placeholder-white bg-transparent border-b border-white focus:outline-none"
                        placeholder="Enter username" />
                </div>
                @error('username')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-5">
                <div class="flex items-center gap-2 text-white">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" id="email" @error('email') is-invalid @enderror
                        class="w-full text-white placeholder-white bg-transparent border-b border-white focus:outline-none"
                        placeholder="Enter email" />
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-5">
                <div class="flex items-center text-white">
                    <i class="mr-2 fas fa-lock"></i>
                    <input type="password" name="password" id="password" @error('password') is-invalid @enderror
                        class="w-full text-white placeholder-white bg-transparent border-b border-white focus:outline-none"
                        placeholder="Enter password" />
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmed Password -->
            <div class="mb-5">
                <div class="flex items-center gap-2 text-white">
                    <i class="fa-solid fa-key"></i>
                    <input type="password" name="confirmed_password" id="password"
                        @error('confirmed_password') is-invalid @enderror
                        class="w-full text-white placeholder-white bg-transparent border-b border-white focus:outline-none"
                        placeholder="Confirm password" />
                </div>
                @error('confirmed_password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-5">
                <div class="flex items-center text-white">
                    <i class="mr-2 fas fa-user-tag"></i>
                    <input type="text" name="role" id="role" @error('role') is-invalid @enderror
                        class="w-full text-white placeholder-white bg-transparent border-b border-white focus:outline-none"
                        placeholder="Enter role" />
                </div>
                @error('role')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Links -->
        <div class="flex justify-between mt-6 text-sm">
            <a href="/login" class="text-white hover:underline">Login Page</a>
            <a href="/forgot-password" class="text-white hover:underline">Forgot password?</a>
        </div>

        <!-- Register Button -->
        <button type="submit"
            class="w-full py-2 mt-6 font-semibold text-indigo-600 transition bg-white rounded-full hover:bg-gray-200">
            Register
        </button>

        <!-- Divider -->
        {{-- <div class="flex items-center justify-center my-4 text-white">
            <hr class="flex-1 border-t border-white" />
            <span class="mx-3 text-sm">or sign up with</span>
            <hr class="flex-1 border-t border-white" />
        </div> --}}

    </form>
</body>


</html>
