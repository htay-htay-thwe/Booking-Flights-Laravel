@extends('layouts.app')

@section('content')
    {{-- main content --}}
    <div class="flex-1 mt-16 overflow-y-auto bg-gray-100 md:mt-0">
        {{-- page title --}}
        <div class="pt-16 bg-gray-800">
            <div class="p-4 text-2xl text-white shadow md:rounded-tl-3xl bg-gradient-to-r from-pink-500 to-pink-600">
                <h3 class="pl-2 font-bold"><i class="fa-solid fa-plane-departure"></i> Upcoming Flights</h3>
            </div>
        </div>
        {{-- page title --}}

        {{-- Flight Form --}}
        <div class="max-w-4xl p-6 mx-auto mt-4 bg-white rounded shadow">
            <h2 class="mb-6 text-2xl font-bold text-gray-800"><i class="fa-solid fa-plane"></i> Create a Flight</h2>

            <form method="POST" action="{{ route('create#flight') }}" method="post">
                @csrf

                {{-- select Airlines --}}
                <div class="max-w-md mb-6">
                    <label for="airlineSelect" class="block mb-1 font-medium text-gray-700">
                        Select Airline
                    </label>
                    <select value="{{ old('airline') }}" id="airlineSelect" name="airline" placeholder="Choose airline..."
                        class="w-full     @error('airline') border-red-600 border-1 @enderror border-gray-500 rounded shadow-sm border-1 hover:border-pink-500"></select>
                </div>


                {{-- from place to place --}}
                <div class="relative mb-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- From -->
                        <div class="px-1 ">
                            <label class="block mb-1 font-medium text-gray-700"><i class="fa-solid fa-plane-departure"></i>
                                From</label>
                            <select value="{{ old('from') }}" name="from" id="fromInput"
                                class="w-full @error('from') border-red-600 border-1 @enderror border-gray-500 rounded shadow-sm border-1 hover:border-pink-500">
                            </select>
                        </div>

                        <!-- To -->
                        <div class="px-1 ">
                            <label class="block mb-1 font-medium text-gray-700"><i class="fa-solid fa-plane-arrival"></i>
                                To</label>
                            <select value="{{ old('to') }}" name="to" id="toInput"
                                class="w-full              @error('to') border-red-600 border-1 @enderror border-gray-500 rounded shadow-sm border-1 hover:border-pink-500">
                            </select>
                        </div>
                    </div>

                    <!-- Swap Button (centered between From/To inputs) -->
                    <button type="button" id="swapBtn"
                        class="absolute z-10 pt-3 pb-3 pr-3 pl-3 transform -translate-x-1/2 -translate-y-1/2 bg-white border-gray-500  border-1 rounded-full shadow-md top-[60%] left-1/2 lg:top-[70%] md:top-[73%] hover:bg-gray-100"
                        title="Swap From & To">
                        <i class="text-gray-600 fas fa-right-left"></i>
                    </button>
                </div>


                <!-- Dates -->
                <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                    <div>
                        <label class="block mb-1 font-medium text-gray-700"> Departure Date</label>
                        <input value="{{ old('departure_date') }}" type="date" name="departure_date"
                            class="w-full         @error('departure_date') border-red-600 border-1 @enderror px-4 py-1.5 hover:border-pink-500 border-gray-500 rounded shadow-sm border-1">
                    </div>
                </div>

                {{-- from time to time --}}
                <div class="relative mb-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- From -->
                        <div>
                            <label class="block mb-1 font-medium text-gray-700"> FromTime</label>
                            <input value="{{ old('fromTime') }}" type="time" name="fromTime" id="fromTimeInput"
                                class="w-full @error('fromTime')
                            border-red-600 border-1 @enderror px-4 py-1.5 hover:border-pink-500 border-gray-500 rounded shadow-sm border-1"
                                placeholder="eg. 14:40">
                        </div>

                        <!-- To -->
                        <div>
                            <label class="block mb-1 font-medium text-gray-700"> ToTime</label>
                            <input type="time" name="toTime" id="toTimeInput" value="{{ old('toTime') }}"
                                class="w-full   @error('toTime') border-red-600 border-1 @enderror px-4 py-1.5 hover:border-pink-500 border-gray-500 rounded shadow-sm border-1"
                                placeholder="e.g. 15:00">
                        </div>
                    </div>

                    <!-- Swap Button (centered between From/To inputs) -->
                    <button type="button" id="swapTimeBtn"
                        class="absolute z-10 pt-3 pb-3 pr-3 pl-3 transform -translate-x-1/2 -translate-y-1/2 bg-white border-gray-500  border-1 rounded-full shadow-md top-[60%] lg:top-[70%] md:top-[73%] left-1/2 hover:bg-gray-100"
                        title="Swap From & To">
                        <i class="text-gray-600 fas fa-right-left"></i>
                    </button>
                </div>
                {{-- price --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-gray-700"> Price Per Person</label>
                    <input type="number" name="price" id="toTimeInput" value="{{ old('price') }}"
                        class="w-full px-4 py-1.5 @error('price') border-red-600 border-1 @enderror border-gray-500 hover:border-pink-500 rounded shadow-sm border-1"
                        placeholder="$ 12">
                </div>

                {{-- flightStatus --}}
                <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">
                    <div>
                        <label class="block mb-1 font-medium text-gray-700">Flight Status</label>
                        <select name="flightStatus" value="{{ old('flightStatus') }}"
                            class="w-full px-4 py-1.5 text-start @error('flightStatus') border-red-600 border-1 @enderror border-gray-500 hover:border-pink-500 rounded shadow-sm border-1"
                            placeholder="$ 12">
                            <option value="active">Active</option>
                            <option value="checkIn">Check In</option>
                            <option value="delayed">Delayed</option>
                            <option value="pass">pass</option>
                        </select>
                    </div>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="w-full py-3 font-semibold text-white transition bg-pink-600 rounded hover:bg-pink-700">
                        Create Flights
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- main content end --}}
@endsection

@section('script')
    <script>
        const swapBtn = document.getElementById('swapBtn');
        const fromInput = document.getElementById('fromInput');
        const toInput = document.getElementById('toInput');

        const swapTimeBtn = document.getElementById('swapTimeBtn');
        const fromTimeInput = document.getElementById('fromTimeInput');
        const toTimeInput = document.getElementById('toTimeInput');

        // Place Swap
        document.getElementById('swapBtn').addEventListener('click', () => {
            if (fromSelect && toSelect) {
                const fromVal = fromSelect.getValue();
                const toVal = toSelect.getValue();

                fromSelect.setValue(toVal);
                toSelect.setValue(fromVal);
            }
        });


        // Time Swap
        swapTimeBtn.addEventListener('click', () => {
            const fromTime = fromTimeInput.value;
            fromTimeInput.value = toTimeInput.value;
            toTimeInput.value = fromTime;
        });

        let fromSelect, toSelect;
        // Tom Select
        fetch('/airports.json')
            .then(response => response.json())
            .then(data => {
                const airportOptions = Object.values(data).map(airport => ({
                    value: airport.icao,
                    text: `${airport.city}, ${airport.country} - ${airport.name} (${airport.iata || airport.icao})`
                }));

                fromSelect = new TomSelect("#fromInput", {
                    options: airportOptions,
                    valueField: "value",
                    labelField: "text",
                    searchField: ["text"],
                    maxOptions: 100,
                    placeholder: "Select an airport",

                });
                toSelect = new TomSelect("#toInput", {
                    options: airportOptions,
                    valueField: "value",
                    labelField: "text",
                    searchField: ["text"],
                    maxOptions: 100,
                    placeholder: "Select an airport",

                });

                // airlines select
                fetch('/airlines.json')
                    .then(res => res.json())
                    .then(data => {
                        const options = Object.entries(data).map(([iata, airline]) => ({
                            value: airline.id,
                            name: airline.name,
                            logo: airline.logo
                        }));


                        const select = new TomSelect("#airlineSelect", {
                            options,
                            valueField: "value",
                            labelField: "name",
                            searchField: ["name"],
                            placeholder: "Choose airline...",
                            maxOptions: 100,
                            render: {
                                option: function(data, escape) {
                                    return `
              <div class="flex items-center space-x-2">
                <img src="${escape(data.logo)}" alt="${escape(data.name)}" class="rounded-full w-7 h-7" />
                <span class="text-md">${escape(data.name)} (${escape(data.value)})</span>
              </div>
            `;
                                },
                                item: function(data, escape) {
                                    return `
              <div class="flex items-center space-x-2">
                <img src="${escape(data.logo)}" alt="${escape(data.name)}" class="rounded-full w-7 h-7" />
                <span>${escape(data.name)} (${escape(data.value)})</span>
              </div>
            `;
                                }
                            }
                        });
                    });
            });
    </script>
@endsection

</html>
