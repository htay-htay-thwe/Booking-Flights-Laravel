<div class="flex-1 mt-16 mb-16  overflow-y-auto  md:mt-0 md:mb-0">
    {{-- page title --}}
    <div class="sticky top-0 z-30 pt-14">
        <div
            class="flex justify-between p-4 rounded-tl-none shadow md:rounded-tl-3xl bg-gradient-to-r from-red-500 to-red-600">
            <h3 class="pl-2 text-lg font-bold text-white sm:text-2xl"><i class=" fa-solid fa-plane-departure"></i> Daily
                Flights</h3>
            <div class="flex items-center justify-end mb-1 space-x-2">
                <label for="sort" class="font-mono font-semibold text-white text-md sm:text-lg">Sort by:</label>
                <select id="sortSelect" name="sort"
                    class="px-3 py-2 text-sm text-white bg-red-600 border rounded shadow-sm focus:ring-pink-500 focus:border-pink-500">
                    <option value="">-- All --</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="duration_asc">Duration: Fastest</option>
                    <option value="duration_desc">Duration: Longest</option>
                </select>
            </div>
        </div>
    </div>
    {{-- page title --}}

    <div class="overflow-x-auto shadow ">
        <table class="min-w-full overflow-y-auto bg-white border border-gray-200 table-auto">
            <thead class="sticky z-20 text-sm text-gray-700 uppercase bg-gray-200">
                <tr>
                    <th class="px-3 py-4 text-left">Airline</th>
                    <th class="px-4 py-3 text-left">From</th>
                    <th class="px-4 py-3 text-left">To</th>
                    <th class="px-4 py-3 text-left">Departure</th>
                    <th class="px-4 py-3 text-left">Arrival</th>
                    <th class="px-4 py-3 text-left">Dep. Date</th>
                    <th class="px-4 py-3 text-left">Duration</th>
                    <th class="px-5 py-3 text-left">Price</th>
                    <th class="px-4 py-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-700" id="flightTableBody">
                @foreach ($pagination as $flight)
                    <tr class="transition duration-200 border-b hover:bg-zinc-100">
                        <!-- Airline -->
                        <td class="flex items-center px-3 py-4 space-x-3">
                            <img src="{{ $flight['airline_logo'] }}" class="object-contain w-6 h-6 md:w-12 md:h-12"
                                alt="Thai Airways" />
                            <div>
                                <div class="font-semibold text-wrap">{{ $flight['airline'] }}</div>
                                <div class="text-sm text-gray-500">TG102</div>
                            </div>
                        </td>

                        <!-- From -->
                        <td class="px-4 py-4 font-mono font-medium text-red-500">{{ $flight['FromCity'] }}
                            <span class="font-medium text-gray-500 text-md">
                                @if ($flight['airport_code_from'] != null)
                                    ({{ $flight['airport_code_from'] }})
                                @endif
                            </span>
                        </td>

                        <!-- To -->
                        <td class="px-4 py-4 font-mono font-medium text-red-500">{{ $flight['ToCity'] }}
                            <span class="font-medium text-gray-500 text-md">
                                @if ($flight['airport_code_to'] != null)
                                    ({{ $flight['airport_code_to'] }})
                                @endif
                            </span>
                        </td>

                        <!-- Departure Time -->
                        <td class="px-4 py-4 text-sm text-gray-600">{{ $flight['fromTime'] }}</td>

                        <!-- Arrival Time -->
                        <td class="px-4 py-4 text-sm text-gray-600">{{ $flight['toTime'] }}</td>

                        <!-- Departure Date -->
                        <td class="px-4 py-4 text-sm text-gray-600">{{ $flight['departure_date'] }}</td>

                        <!-- Duration -->
                        <td class="px-4 py-4 text-sm"><span class="font-medium">{{ $flight['duration'] }}</span>
                        </td>

                        <!-- Price -->
                        <td class="px-5 py-4 font-bold text-pink-600 text-md">${{ $flight['price'] }}</td>

                        <!-- Action -->
                        <td class="px-4 py-4 text-center align-middle">
                            <div class="flex items-center justify-center gap-3">
                                <div class="relative inline-block group">
                                    <a href="/flight/delete/{{ $flight['id'] }}"
                                        class="text-2xl text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                    <div class="absolute hidden px-2 py-1 mt-2 text-xs text-white bg-red-500 rounded top-full group-hover:block whitespace-nowrap"
                                        style="left: 50%; transform: translateX(-50%)">
                                        Delete
                                    </div>
                                </div>
                                <div class="relative inline-block group">
                                    <a href="/flight/edit/{{ $flight['id'] }}"
                                        class="text-2xl text-purple-600 hover:text-purple-800">
                                        <i class="fa-solid fa-file-pen"></i>
                                    </a>
                                    <div class="absolute z-40 hidden px-2 py-1 mt-2 text-xs text-white bg-purple-600 rounded top-full group-hover:block whitespace-nowrap"
                                        style="left: 50%; transform: translateX(-50%)">
                                        Edit
                                    </div>
                                </div>
                                <div class="relative inline-block group">
                                    <a href="/flight/details/page/{{ $flight['id'] }}"
                                        class="text-2xl text-pink-500 hover:text-pink-600">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </a>
                                    <div class="absolute hidden px-2 py-1 mt-2 text-xs text-white bg-pink-500 rounded top-full group-hover:block whitespace-nowrap"
                                        style="left: 50%; transform: translateX(-50%)">
                                        Details
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <div class="flex justify-end p-6" id="defaultPagination">
        {{ $pagination->links('pagination::tailwind') }}
    </div>

    <div class="flex justify-end hidden p-6 space-x-2 pagination-js" id="ajaxPagination">
        {{-- JS will populate buttons here --}}
    </div>


    @if ($pagination == null || count($pagination) == 0)
        <div class="flex items-center justify-center h-screen">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('/img/noflight.svg') }}" class="w-40 h-40" />
                <p class="text-lg font-semibold text-gray-500">No Flight Available!</p>
            </div>
        </div>
    @endif
</div>
