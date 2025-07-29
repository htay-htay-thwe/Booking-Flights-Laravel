@extends('layouts.app')

@section('content')
    <div class="flex-1 mt-16 overflow-y-auto bg-gray-100 md:mt-0">

        {{-- page title --}}
        <div class="sticky top-0 pt-16 bg-gray-800">
            <div class="p-4 text-2xl text-white shadow md:rounded-tl-3xl bg-gradient-to-r from-pink-500 to-pink-600">
                <h3 class="pl-2 font-bold"> <i class=" fa-solid fa-plane-departure"></i> Flight Information Detail
                    page</h3>
            </div>
        </div>
        {{-- page title --}}

        {{-- Flight Header --}}
        <div class="sticky pt-5 pl-10 pr-10 mb-8 bg-white shadow top-32 pb-7">
            <div onclick="history.back()" class="flex gap-2 mb-2 text-xl hover:text-gray-600">
                <div> <i class="fa-solid fa-backward"></i></div>
                <div>Back</div>
            </div>
            <h1 class="mb-4 font-sans text-3xl font-bold">
                Flight #TG102 - {{ $flight['FromCity'] }}
                @if ($flight['airport_code_from'])
                    ({{ $flight['airport_code_from'] }})
                @endif
                <span class="text-xl text-pink-400"><i class="fa-solid fa-plane"></i></span>
                {{ $flight['ToCity'] }}
                @if ($flight['airport_code_to'])
                    ({{ $flight['airport_code_to'] }})
                @endif
            </h1>
            <div class="grid grid-cols-1 gap-6 text-sm text-gray-700 md:grid-cols-4">
                <div>
                    <h3 class="mb-1 font-semibold">Dates & Times</h3>
                    <p><strong>Departure:</strong> {{ $flight['departure_date'] }} , {{ $flight['fromTime'] }}</p>
                    <p><strong>Arrival:</strong> {{ $flight['departure_date'] }} , {{ $flight['toTime'] }}</p>
                </div>
                <div>
                    <h3 class="mb-1 font-semibold">Aircraft & Duration</h3>
                    <p>Boeing 777</p>
                    <p>Duration: {{ $flight['duration'] }}</p>
                </div>
                <div>
                    <h3 class="mb-1 font-semibold">Booking Summary</h3>
                    <p><strong>Total Bookings:</strong> {{ count($reserve) }}</p>
                    <p><strong>Confirmed:</strong> {{ $confirmedCount }}</p>
                    <p><strong>Canceled:</strong> {{ $canceledCount }}</p>
                </div>
                <div>
                    <h3 class="mb-1 font-semibold">Booking Seats</h3>
                    <p><strong>PreBooked with Seats No.:</strong> {{ $bookedSeatCount }}</p>
                    <p><strong>left Seats:</strong> {{ 186 - count($reserve) }}</p>
                    <p class="text-blue-500 hover:text-blue-400">
                        <a href="{{ route('seats#show', $flight['id']) }}">Manage Seat Layouts</a>
                    </p>
                </div>
            </div>
        </div>

        {{-- Search / Filter --}}
        <div class="flex flex-col gap-4 p-4 mb-6 -z-10 md:flex-row md:items-center md:justify-between">
            <input type="text"
                placeholder="Search passenger name, email, booking status, seat, class or paymentStatus..."
                class="flex-grow px-4 py-2 border-pink-500 rounded shadow border-1 focus:ring focus:ring-pink-300"
                id="searchInput" />
        </div>

        {{-- Passenger Table --}}
        <div class="overflow-x-auto bg-white rounded shadow -z-10">
            <table class="min-w-full text-left border border-collapse border-gray-200 table-auto">
                <thead class="bg-gray-200 ">
                    <tr>
                        <th class="px-4 py-3 border border-gray-300">Passenger Name</th>
                        <th class="px-4 py-3 border border-gray-300">Email</th>
                        <th class="px-4 py-3 border border-gray-300">Booking Status</th>
                        <th class="px-4 py-3 border border-gray-300">Class</th>
                        <th class="px-4 py-3 border border-gray-300">Baggage</th>
                        <th class="px-4 py-3 border border-gray-300">Check-In Status</th>
                        <th class="px-4 py-3 border border-gray-300">Seat</th>
                        <th class="px-4 py-3 border border-gray-300">Payment Status</th>
                        <th class="px-4 py-3 border border-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody id="passengerTableBody" class="text-gray-700">
                    {{-- Static rows example --}}
                    @if (count($reserve) > 0)
                        @foreach ($reserve as $flight)
                            <tr id="passengerTableBody" class="border border-gray-300 hover:bg-gray-100"
                                data-status="Confirmed">
                                <td class="px-4 py-3">{{ $flight['passenger_first_name'] }}
                                    {{ $flight['passenger_last_name'] }}</td>
                                <td class="px-4 py-3">{{ $flight['email'] }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-semibold {{ ($flight['bookStatus'] == 'pending' ? 'text-purple-500 bg-purple-200' : $flight['bookStatus'] == 'confirmed') ? 'text-green-500 bg-green-200' : 'text-red-500 bg-red-200' }}   rounded-full">{{ $flight['bookStatus'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    {{ $flight['class'] }} <div> USD
                                        @if ($flight['classPrice'] !== null)
                                            {{ $flight['classPrice'] }}
                                        @else
                                            0
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-md">
                                    @if ($flight['kg'] !== null)
                                        {{ $flight['kg'] }} (Extra Bag with Hand Bag)
                                    @else
                                        Hand Bag (7kg)
                                    @endif
                                </td>
                                <td
                                    class="px-4 py-3 text-sm font-semibold text-center {{ $flight['checkInStatus'] == 'pending' ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $flight['checkInStatus'] }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if ($flight['seat'] !== null)
                                        {{ $flight['seat'] }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-block px-2 py-1 text-xs text-green-700 bg-green-100 rounded">Paid</span>
                                </td>
                                <td class="flex-col px-4 py-3 space-x-2 text-sm">

                                    <div class="w-full">
                                        @if ($flight['bookStatus'] != 'confirmed')
                                            <div>
                                                {{-- <a href="https://mail.google.com/mail/?view=cm&to={{ $flight['email'] }}" --}}
                                                <a href="{{ route('email#sent', $flight->id) }}"
                                                    class="text-blue-600 hover:underline">
                                                    Confirmed
                                                </a>
                                            </div>
                                        @endif
                                        @if ($flight['checkInStatus'] != 'checkIn' && $flight['bookStatus'] == 'confirmed')
                                            <div>
                                                <a href="{{ route('update#checkIn', $flight->id) }}"
                                                    class="text-yellow-600 hover:underline">
                                                    Check In
                                                </a>
                                            </div>
                                        @endif
                                        @if ($flight['bookStatus'] != 'confirmed')
                                            <div>
                                                <a href="{{ route('cancel#booking', $flight->id) }}"
                                                    class="text-red-600 hover:underline">
                                                    Cancel
                                                </a>
                                            </div>
                                        @else
                                            <div class="font-semibold text-black text-md">Safe Fly!</div>
                                        @endif
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="px-4 py-3 text-gray-400 text-center">
                                There is no Booking Data!
                            </td>
                        </tr>
                    @endif
                    {{-- Add more passengers as needed --}}
                </tbody>
            </table>
        </div>

        {{-- pagination --}}
        <div class="flex justify-end max-w-4xl p-6 mx-auto" id="defaultPagination">
            {{ $reserve->links('pagination::tailwind') }}
        </div>

        <div class="flex justify-end hidden max-w-4xl p-6 mx-auto space-x-2 pagination-js" id="ajaxPagination">
            {{-- JS will populate buttons here --}}
        </div>
        {{-- pagination end --}}



    </div>
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let currentSort = '';
            let currentPage = 1;
            const searchInput = document.getElementById('searchInput');
            const ajaxPagination = document.getElementById('ajaxPagination');
            const defaultPagination = document.getElementById('defaultPagination');

            searchInput.addEventListener('input', function() {
                currentPage = 1; // reset page on search
                const query = searchInput.value.trim();
                fetchPassengers(query, currentPage);
            });

            function fetchPassengers(query, page) {
                let url = '';

                if (query) {
                    url = `/flight/passenger/search?query=${encodeURIComponent(query)}&page=${page}`;
                } else {
                    location.reload();
                    return;
                }

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        const tbody = document.getElementById('passengerTableBody');
                        tbody.innerHTML = '';

                        data.data.forEach(flight => {
                            tbody.innerHTML += `
                        <tr class="border border-gray-300 hover:bg-gray-100" data-status="Confirmed">
                            <td class="px-4 py-3">${flight.passenger_first_name} ${flight.passenger_last_name}</td>
                            <td class="px-4 py-3">${flight.email}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 text-xs font-semibold ${
                                    flight.bookStatus === 'pending' ? 'text-purple-500 bg-purple-200' :
                                    flight.bookStatus === 'confirmed' ? 'text-green-500 bg-green-200' :
                                    'text-red-500 bg-red-200'
                                } rounded-full">${flight.bookStatus}</span>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                ${flight.class}
                                <div>USD ${flight.classPrice !== null ? flight.classPrice : 0}</div>
                            </td>
                            <td class="px-4 py-3 text-md">
                                ${flight.kg !== null ? `${flight.kg} (Extra Bag with Hand Bag)` : `Hand Bag (7kg)`}
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-center ${
                                flight.checkInStatus === 'pending' ? 'text-red-600' : 'text-green-600'
                            }">
                                ${flight.checkInStatus}
                            </td>
                            <td class="px-4 py-3 text-center">${flight.seat !== null ? flight.seat : '-'}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 text-xs text-green-700 bg-green-100 rounded">Paid</span>
                            </td>
                            <td class="px-4 py-3 text-sm space-y-1">
                                ${
                                    flight.bookStatus === 'confirmed' && flight.checkInStatus === 'checkIn'
                                        ? `<div class="font-semibold text-black text-md">Safe Fly!</div>`
                                        : `
                                                                                                                            ${flight.bookStatus !== 'confirmed' ? `<div><a href="/email/sent/${flight.id}" class="text-blue-600 hover:underline">Confirmed</a></div>` : ''}
                                                                                                                            ${flight.checkInStatus !== 'checkIn' ? `<div><a href="/update/checkIn/${flight.id}" class="text-yellow-600 hover:underline">Check In</a></div>` : ''}
                                                                                                                            ${flight.bookStatus !== 'confirmed' ? `<div><a href="/cancel/booking/${flight.id}" class="text-red-600 hover:underline">Cancel</a></div>` : ''}
                                                                                                                        `
                                }
                            </td>
                        </tr>
                    `;
                        });

                        renderPagination(data);
                        defaultPagination.classList.add('hidden');
                        ajaxPagination.classList.remove('hidden');
                    })
                    .catch(err => console.error('Error:', err));
            }

            window.fetchPassengers = fetchPassengers;

            function renderPagination(pagination) {
                const container = document.querySelector('.pagination-js');
                container.innerHTML = '';

                for (let i = 1; i <= pagination.last_page; i++) {
                    container.innerHTML += `
                <button
                    class="px-3 py-1 border rounded ${i === pagination.current_page ? 'bg-red-600 text-white hover:bg-red-500' : 'bg-white text-gray-700 hover:bg-gray-100'}"
                    onclick="fetchPassengers('${searchInput.value.trim()}', ${i})"
                >
                    ${i}
                </button>
            `;
                }
            }
            // Initially show default pagination
            defaultPagination.classList.remove('hidden');

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

            @if (session('checkIn'))
                notyf.success(@json(session('checkIn')));
            @endif

            @if (session('emailSent'))
                notyf.success(@json(session('emailSent')));
            @endif

            @if (session('cancel'))
                notyf.success(@json(session('cancel')));
            @endif
        });
    </script>
@endsection
