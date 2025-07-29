<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow">
        @if (session('status'))
            <div class="text-green-600">{{ session('status') }}</div>
        @endif

        @if (session('error'))
            <div class="text-red-600">{{ session('error') }}</div>
        @endif

        <form wire:submit.prevent="resetPassword">
            <input type="hidden" wire:model="token">

            <div class="mb-4">
                <label for="email" class="block mb-1">Email</label>
                <input wire:model.defer="email" type="email" class="w-full p-2 border rounded">
                @error('email')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-1">New Password</label>
                <input wire:model.defer="password" type="password" class="w-full p-2 border rounded">
                @error('password')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block mb-1">Confirm Password</label>
                <input wire:model.defer="password_confirmation" type="password" class="w-full p-2 border rounded">
            </div>

            <button type="submit" class="w-full px-4 py-2 text-white bg-indigo-500 rounded hover:bg-indigo-600">
                Reset Password
            </button>
        </form>
    </div>
</div>
