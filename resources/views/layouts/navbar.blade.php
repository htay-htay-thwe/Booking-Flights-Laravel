<!--Nav-->
<nav class="fixed top-0 z-20 w-full h-auto px-1 pt-2 pb-1 mt-0 bg-gray-800 md:pt-1">

    <div class="flex flex-wrap items-center justify-between">
        <div class="flex justify-center flex-shrink ml-5 text-white md:w-1/3 md:justify-start">
            <a href="#">
                <div class="flex gap-3 text-amber-600">
                    <img src="{{ asset('img/flight-logo.png') }}" class="w-12 h-12" alt="Flight Logo" />
                    <div class="mt-3 font-semibold"> Flights</div>
                </div>
            </a>
        </div>


        <div class="flex content-center justify-between w-full pt-2 md:w-1/3 md:justify-end">
            <ul class="flex items-center justify-between flex-1 list-reset md:flex-none">

                <li class="flex-1 md:flex-none md:mr-3">
                    <div class="relative inline-block">

                        <!-- Dropdown Button -->
                        <div onclick="toggleDD('myDropdown')"
                            class="relative flex items-center gap-2 cursor-pointer drop-button" id="dropdownToggle">
                            <span class="pr-2">
                                @if (Auth::user()->image)
                                    <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="Profile Image"
                                        class="w-8 h-8 rounded-full">
                                @else
                                    <img src="{{ asset('img/default.png') }}" alt="Default Profile Image"
                                        class="w-8 h-8 rounded-full">
                                @endif
                            </span>
                            <span class="text-white">Hi, {{ Auth::user()->name }}</span>
                            <svg class="inline h-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>

                        <!-- Dropdown Menu -->
                        <div id="myDropdown"
                            class="absolute right-0 z-30 invisible p-3 overflow-auto text-white bg-gray-800 w-44 top-12 dropdownlist">
                            <a href="/settings"
                                class="block p-2 text-sm text-white no-underline hover:bg-gray-700 hover:no-underline">
                                <i class="fa fa-cog fa-fw"></i> Settings
                            </a>
                            <div class="my-2 border border-gray-700"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="block w-full p-2 text-sm text-left text-white no-underline hover:bg-gray-700 hover:no-underline">
                                    <i class="fas fa-sign-out-alt fa-fw"></i> Log Out
                                </button>
                            </form>
                        </div>

                    </div>
                </li>
            </ul>
        </div>
    </div>

</nav>

<script>
    function toggleDD(dropdownID) {
        document.getElementById(dropdownID).classList.toggle("invisible");
    }

    window.addEventListener("click", function(event) {
        const toggle = document.getElementById("dropdownToggle");
        const dropdown = document.getElementById("myDropdown");

        if (!toggle.contains(event.target)) {
            dropdown.classList.add("invisible");
        }
    });
</script>
