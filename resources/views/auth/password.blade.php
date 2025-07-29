<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>password change</title>
    @include('layouts.cdn')
</head>

<body>
    @if (session('success'))
        <div class="mb-2 text-sm text-green-500">{{ session('success') }}</div>
    @endif
    @if (session('failed'))
        <div class="mb-2 text-sm text-red-500">{{ session('failed') }}</div>
    @endif
    <form method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Current Password</label>
            <input type="password" name="current"
                class="block w-full p-3 mt-1 border border-gray-300 rounded-md shadow-sm">
            @error('current')
                <p class="text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" name="password"
                class="block w-full p-3 mt-1 border border-gray-300 rounded-md shadow-sm">
            @error('password')
                <p class="text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation"
                class="block w-full p-3 mt-1 border border-gray-300 rounded-md shadow-sm">
        </div>

        <button type="submit" class="p-3 px-4 py-2 mt-4 text-white bg-blue-600 rounded hover:bg-blue-700">
            Save Changes
        </button>
    </form>
</body>

</html>
