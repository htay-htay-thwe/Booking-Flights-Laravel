@extends('layouts.app')

@section('content')
    <div class="flex-1 mt-16 overflow-y-auto bg-gray-100 md:mt-0">

        {{-- page title --}}
        <div class="sticky top-0 z-10 pt-16 bg-gray-800">
            <div class="flex justify-between p-4 shadow md:rounded-tl-3xl bg-gradient-to-r from-red-500 to-red-600">
                <h3 class="pl-2 text-lg font-bold text-white sm:text-2xl">
                    <i class="fa fa-cog fa-fw"></i> Settings
                </h3>
            </div>
        </div>

        <div class="max-w-3xl p-6 mx-auto mt-5 bg-white shadow rounded-xl">
            <h2 class="mb-6 text-2xl font-semibold">Settings</h2>

            {{-- Tabs --}}
            <div class="flex mb-6 space-x-4 border-b">
                @php $tab = request('tab', 'account'); @endphp

                <a href="{{ route('settings', ['tab' => 'account']) }}"
                    class="pb-2 {{ $tab === 'account' ? 'border-b-2 border-blue-600 text-blue-600 font-medium' : 'text-gray-500' }}">
                    Account
                </a>
                <a href="{{ route('settings', ['tab' => 'password']) }}"
                    class="pb-2 {{ $tab === 'password' ? 'border-b-2 border-blue-600 text-blue-600 font-medium' : 'text-gray-500' }}">
                    Change Password
                </a>
                <a href="{{ route('settings', ['tab' => 'email']) }}"
                    class="pb-2 {{ $tab === 'email' ? 'border-b-2 border-blue-600 text-blue-600 font-medium' : 'text-gray-500' }}">
                    Change Email
                </a>
                <a href="{{ route('settings', ['tab' => 'profile']) }}"
                    class="pb-2 {{ $tab === 'profile' ? 'border-b-2 border-blue-600 text-blue-600 font-medium' : 'text-gray-500' }}">
                    Change Profile
                </a>
            </div>

            {{-- Tab Contents --}}
            @if ($tab === 'account')
                <div class="space-y-4">
                    <div>
                        @if (Auth::user()->image)
                            <img src="{{ asset('storage/' . Auth::user()->image) }}" class="w-20 h-20 rounded-full">
                        @else
                            <img src="{{ asset('img/default.png') }}" class="w-20 h-20 rounded-full">
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" value="{{ Auth::user()->name }}" readonly
                            class="block w-full p-3 mt-1 bg-gray-100 border-gray-300 rounded-md shadow-sm cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" value="{{ Auth::user()->email }}" readonly
                            class="block w-full p-3 mt-1 bg-gray-100 border-gray-300 rounded-md shadow-sm cursor-not-allowed">
                    </div>
                </div>
            @endif

            @if ($tab === 'password')
                @if (session('success'))
                    <div class="mb-2 text-sm text-green-500">{{ session('success') }}</div>
                @endif
                @if (session('failed'))
                    <div class="mb-2 text-sm text-red-500">{{ session('failed') }}</div>
                @endif
                <form method="POST" action="{{ route('settings#password') }}" class="space-y-4">
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
            @endif

            @if ($tab === 'email')
                @if (session('email'))
                    <div class="mb-2 text-sm text-green-500">{{ session('email') }}</div>
                @endif
                <form method="POST" action="{{ route('settings#email') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}"
                            class="block w-full p-3 mt-1 bg-gray-100 border-gray-300 rounded-md shadow-sm ">
                        @error('name')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}"
                            class="block w-full p-3 mt-1 border-gray-300 rounded-md shadow-sm">
                        @error('email')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="p-3 px-4 py-2 mt-4 text-white bg-blue-600 rounded hover:bg-blue-700">
                        Save Changes
                    </button>
                </form>
            @endif

            @if ($tab === 'profile')
                @if (session('profile'))
                    <div class="mb-2 text-sm text-green-500">{{ session('profile') }}</div>
                @endif
                <form method="POST" action="{{ route('settings#profile') }}" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    <!-- Profile Image Preview -->
                    <div>
                        <img id="imagePreview"
                            src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('img/default.png') }}"
                            alt="Preview" class="object-cover w-20 h-20 rounded-full">
                    </div>

                    <!-- Image Upload Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Profile Picture</label>
                        <input type="file" name="image" accept="image/*" onchange="previewImage(event)"
                            class="block w-full p-3 mt-1 bg-white border border-gray-300 rounded-md shadow-sm">
                        @error('image')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Submit Button -->
                    <button class="px-4 py-2 mt-4 text-white bg-blue-600 rounded hover:bg-blue-700">
                        Save Changes
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const imagePreview = document.getElementById('imagePreview');

            reader.onload = function() {
                imagePreview.src = reader.result;
            }

            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
@endsection
