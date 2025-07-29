@extends('layouts.app')

@section('content')
    {{-- main content --}}
    <div class="flex-1 mt-16 overflow-y-auto bg-gray-100 md:mt-0">


        {{-- page title --}}
        <div class="sticky top-0 z-10 pt-16 bg-gray-800">
            <div class="flex justify-between p-4 shadow md:rounded-tl-3xl bg-gradient-to-r from-red-500 to-red-600">
                <h3 class="pl-2 text-lg font-bold text-white sm:text-2xl">
                    <i class="mr-1 fa-solid fa-plane-departure"></i> Daily Flights
                </h3>

                <div class="flex gap-5">
                    <div>
                        <input type="text" placeholder="Search ..."
                            class="flex-grow px-4 py-2 bg-white border-pink-500 rounded shadow border-1 focus:ring focus:ring-pink-300"
                            id="searchInput" />
                    </div>
                    <div class="flex items-center space-x-2">
                        <label for="sortSelect" class="font-mono font-semibold text-white text-md sm:text-lg">Sort
                            by:</label>
                        <select id="sortSelect" name="sort"
                            class="px-3 py-2 text-sm text-white bg-red-600 border-2 border-red-700 rounded shadow-sm focus:ring-pink-500 focus:border-pink-500">
                            <option value="">-- All --</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="duration_asc">Duration: Fastest</option>
                            <option value="duration_desc">Duration: Longest</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        {{-- page title end --}}

        {{-- table  --}}
        <div class="w-full mx-auto overflow-x-auto shadow md:max-w-3xl lg:max-w-full">
            <table class="w-full overflow-y-auto bg-white border border-gray-200 table-auto">
                <thead class="text-sm text-gray-700 uppercase bg-gray-200 -z-10">
                    <tr>
                        <th class="px-3 py-4 text-left">Airline</th>
                        <th class="px-4 py-3 text-left">From</th>
                        <th class="px-4 py-3 text-left">To</th>
                        <th class="px-4 py-3 text-left">Dep:</th>
                        <th class="px-4 py-3 text-left">Arr:</th>
                        <th class="px-4 py-3 text-left">Dep. Date</th>
                        <th class="px-4 py-3 text-left">Duration</th>
                        <th class="px-5 py-3 text-left">Price</th>
                        <th class="px-5 py-3 text-left">Status</th>
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

                            {{-- flight Status --}}
                            <td class="px-5 py-4 font-bold text-pink-600 text-md">
                                <div
                                    class="p-1 text-sm text-center rounded
                                    {{ in_array($flight['flightStatus'], ['cancel', 'delayed'])
                                        ? 'bg-red-200 text-red-800'
                                        : ($flight['flightStatus'] == 'checkIn'
                                            ? 'bg-orange-200 text-orange-700'
                                            : 'bg-green-200 text-green-800') }}">
                                    {{ strtoupper($flight['flightStatus']) }}
                                </div>

                            </td>

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
        {{-- table end --}}

        {{-- pagination --}}
        <div class="flex justify-end max-w-4xl p-6 mx-auto" id="defaultPagination">
            {{ $pagination->links('pagination::tailwind') }}
        </div>

        <div class="flex justify-end hidden max-w-4xl p-6 mx-auto space-x-2 pagination-js" id="ajaxPagination">
            {{-- JS will populate buttons here --}}
        </div>
        {{-- pagination end --}}

        @if ($pagination == null || count($pagination) == 0)
            <div class="flex items-center justify-center h-screen max-w-4xl mx-auto">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('/img/noflight.svg') }}" class="w-40 h-40" />
                    <p class="text-lg font-semibold text-gray-500">No Flight Available!</p>
                </div>
            </div>
        @endif

    </div>
    </div>
    {{-- main content end --}}
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Global state ---
            let currentSort = '';
            let currentPage = 1;
            let query = '';

            const defaultPagination = document.getElementById('defaultPagination');
            const ajaxPagination = document.getElementById('ajaxPagination');
            const searchInput = document.getElementById('searchInput');
            const sortSelect = document.getElementById('sortSelect'); // Make sure your dropdown has this ID
            const tbody = document.getElementById('flightTableBody');

            // --- Notification ---
            const notyf = new Notyf({
                duration: 4000,
                position: {
                    x: 'right',
                    y: 'top'
                },
                types: [{
                    type: 'success',
                    background: '#10b981',
                    icon: {
                        className: 'fas fa-check',
                        tagName: 'i',
                        color: 'white'
                    }
                }]
            });

            @if (session('success'))
                notyf.success(@json(session('success')));
            @endif

            @if (session('delete'))
                notyf.success(@json(session('delete')));
            @endif

            // --- Search ---
            searchInput?.addEventListener('input', () => {
                query = searchInput.value.trim();
                currentPage = 1;
                fetchFlights(currentSort, currentPage, query);
            });

            // --- Sort ---
            sortSelect?.addEventListener('change', function() {
                currentSort = this.value;
                currentPage = 1;

                if (currentSort === '') {
                    window.location.reload();
                } else {
                    fetchFlights(currentSort, currentPage, query);
                }
            });

            // --- Fetch flights ---
            function fetchFlights(sort = '', page = 1, query = '') {
                let url = '';

                if (query !== '') {
                    url = `/flight/search?query=${encodeURIComponent(query)}&page=${page}`;
                } else if (sort !== '') {
                    url = `/flight/sort?sort=${sort}&page=${page}`;
                } else {
                    window.location.reload();
                    return;
                }

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        renderTable(data.data);
                        renderPagination(data);
                        defaultPagination.classList.add('hidden');
                        ajaxPagination.classList.remove('hidden');
                    })
                    .catch(err => console.error('Error:', err));
            }

            // --- Render table body ---
            function renderTable(flights) {
                tbody.innerHTML = '';
                flights.forEach(flight => {
                    tbody.innerHTML += `
                <tr class="transition duration-200 border-b hover:bg-zinc-100">
                    <td class="flex items-center px-4 py-3 space-x-3">
                        <img src="${flight.airline_logo}" class="object-contain w-6 h-6 md:w-12 md:h-12" alt="${flight.airline}" />
                        <div>
                            <div class="font-semibold">${flight.airline}</div>
                            <div class="text-sm text-gray-500">TG102</div>
                        </div>
                    </td>
                    <td class="px-4 py-3 font-mono font-medium text-red-500">
                        ${flight.FromCity} ${flight.airport_code_from ? `(${flight.airport_code_from})` : ''}
                    </td>
                    <td class="px-4 py-3 font-mono font-medium text-red-500">
                        ${flight.ToCity} ${flight.airport_code_to ? `(${flight.airport_code_to})` : ''}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">${flight.fromTime}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${flight.toTime}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${flight.departure_date}</td>
                    <td class="px-4 py-3 text-sm"><span class="font-medium">${flight.duration}</span></td>
                    <td class="px-4 py-3 font-bold text-pink-600 text-md">$${flight.price}</td>
                    <td class="px-5 py-4 font-bold text-pink-600 text-md">
                        <div class="p-1 text-sm text-center rounded ${
                            flight.flightStatus === 'checkIn' ? 'bg-orange-200 text-orange-700' :
                            flight.flightStatus === 'delayed' ? 'bg-red-200 text-red-800' :
                            'bg-green-200 text-green-800'
                        }">
                            ${flight.flightStatus?.toUpperCase()}
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center align-middle">
                        <div class="flex items-center justify-center gap-4">
                            <a href="/flight/delete/${flight.id}" class="text-2xl text-red-600 hover:text-red-800">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                            <a href="/flight/edit/${flight.id}" class="text-2xl text-purple-600 hover:text-purple-800">
                                <i class="fa-solid fa-file-pen"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                `;
                });
            }

            // --- Render pagination ---
            function renderPagination(data) {
                const container = document.querySelector('.pagination-js');
                container.innerHTML = '';

                for (let i = 1; i <= data.last_page; i++) {
                    const button = document.createElement('button');
                    button.className =
                        `px-3 py-1 border rounded  ${i === data.current_page ? 'bg-red-600 text-white hover:bg-red-500' : 'bg-white text-gray-700 hover:bg-gray-100'}`;
                    button.textContent = i;

                    // FIXED: bind working click event
                    button.addEventListener('click', () => {
                        currentPage = i;
                        fetchFlights(currentSort, currentPage, query); // recall fetch with new page
                    });

                    container.appendChild(button);
                }
            }


            // Expose fetchFlights globally if needed
            window.fetchFlights = fetchFlights;
        });
    </script>
@endsection
