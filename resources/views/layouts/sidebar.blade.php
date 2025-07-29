<div class="fixed bottom-0 z-10 w-full h-16 bg-gray-800 shadow-xl md:w-48 md:h-screen md:relative md:bottom-auto">

    <div class="content-center justify-between text-left md:mt-12 md:w-48 md:fixed md:left-0 md:top-0 md:content-start">
        <ul class="flex flex-row px-1 py-0 text-center list-reset md:flex-col md:py-3 md:px-2 md:text-left">
            <li class="flex-1 mr-3">
                <a href="/dashboard"
                    class="block py-1 pl-1 text-white no-underline align-middle border-b-2 {{ request()->routeIs('dashboard#page') ? 'border-blue-600 ' : 'border-gray-800' }}  md:py-3 hover:text-white hover:border-blue-600">
                    <i
                        class="pr-0 {{ request()->routeIs('dashboard#page') ? 'text-blue-600 ' : 'text-white' }}   fas fa-chart-area md:pr-3"></i><span
                        class="block pb-1 text-xs text-white md:pb-0 md:text-base md:text-white md:inline-block">Analytics</span>
                </a>
            </li>
            <li class="flex-1 mr-3">
                <a href="/flight/lists"
                    class="block py-1 pl-1 text-white no-underline align-middle border-b-2 {{ request()->routeIs('list#page') ? 'border-red-600 ' : 'border-gray-800' }}  md:py-3 hover:text-white hover:border-red-500">
                    <i
                        class="fa-solid fa-plane-departure md:pr-3 {{ request()->routeIs('list#page') ? 'text-red-600 ' : 'text-white' }}"></i><span
                        class="block pb-1 text-xs text-gray-600 md:pb-0 md:text-base md:text-gray-400 md:inline-block">Flights</span>
                </a>
            </li>


            <li class="flex-1 mr-3">
                <a href="/flight/create/page"
                    class="block py-1 pl-1 text-white no-underline align-middle border-b-2 {{ request()->routeIs('create#flight#page') ? 'border-pink-500 ' : 'border-gray-800' }}  md:py-3 hover:text-white hover:border-pink-500">
                    <i
                        class="fa-solid fa-file-pen md:pr-3 {{ request()->routeIs('create#flight#page') ? 'text-pink-600 ' : 'text-white' }}  "></i><span
                        class="block pb-1 text-xs text-gray-600 md:pb-0 md:text-base md:text-gray-400 md:inline-block">Upcoming
                        Flights</span>
                </a>
            </li>


            {{-- <li class="flex-1 mr-3">
                <a href="#"
                    class="block py-1 pl-1 text-white no-underline align-middle border-b-2 border-gray-800 md:py-3 hover:text-white hover:border-purple-500">
                    <i class="fa-solid fa-users md:pr-3"></i></i><span
                        class="block pb-1 text-xs text-gray-600 md:pb-0 md:text-base md:text-gray-400 md:inline-block">Booked
                        Users</span>
                </a>
            </li>

            <li class="flex-1 mr-3">
                <a href="#"
                    class="block py-1 pl-0 text-white no-underline align-middle border-b-2 border-gray-800 md:py-3 md:pl-1 hover:text-white hover:border-red-500">
                    <i class="fa-solid fa-cart-shopping md:pr-3"></i><span
                        class="block pb-1 text-xs text-gray-600 md:pb-0 md:text-base md:text-gray-400 md:inline-block">Cart
                        Flights</span>
                </a>
            </li>

            <li class="flex-1 mr-3">
                <a href="#"
                    class="block py-1 pl-0 text-white no-underline align-middle border-b-2 border-gray-800 md:py-3 md:pl-1 hover:text-white hover:border-blue-500">
                    <i class="pr-0 fa fa-wallet md:pr-3"></i><span
                        class="block pb-1 text-xs text-gray-600 md:pb-0 md:text-base md:text-gray-400 md:inline-block">Payments</span>
                </a>
            </li> --}}

        </ul>
    </div>
</div>
