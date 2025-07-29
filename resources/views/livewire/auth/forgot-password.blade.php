<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow">
        {{-- ✅ Success Message --}}
        @if (session('status'))
            <div class="mb-4 font-semibold text-green-600">
                {{ session('status') }}
            </div>
        @endif



        <form wire:submit.prevent="sendResetLink">
            <div class="mb-4">
                <label for="email" class="block mb-1 font-semibold">Email</label>
                <input placeholder="Enter Email ..." wire:model.defer="email" type="email" id="email"
                    class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                @error('email')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                Send Reset Link
            </button>
        </form>
        <div class="mt-3 text-sm text-gray-500 text-start">We'll send reset link to this email!</div>
    </div>
</div>
