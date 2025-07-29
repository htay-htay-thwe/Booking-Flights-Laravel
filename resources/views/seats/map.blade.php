@extends('layouts.app')

@section('content')
    <div class="flex-1 mt-16 overflow-y-auto bg-gray-100 md:mt-0">

        {{-- Header --}}
        <div class="sticky top-0 z-10 pt-16 bg-gray-800">
            <div class="flex justify-between p-4 shadow md:rounded-tl-3xl bg-gradient-to-r from-red-500 to-red-600">
                <h3 class="pl-2 text-lg font-bold text-white sm:text-2xl">
                    <i class="mr-1 fa-solid fa-plane-departure"></i> Seat Management
                </h3>
                <div class="flex gap-3 text-xl font-semibold text-white">
                    {{ $flightData['FromCity'] }}
                    <div class="text-green-400">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                    {{ $flightData['ToCity'] }}
                </div>
                <div class="text-xl font-semibold text-white">
                    {{ $flightData['fromTime'] }} - {{ $flightData['toTime'] }}
                </div>
            </div>
        </div>

        <div class="container p-2 mx-auto">
            {{-- Flash messages --}}
            @if (session('success'))
                <script>
                    Toastify({
                        text: "{{ session('success') }}",
                        duration: 4000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: " #96c93d",
                            marginTop: "120px",
                        }
                    }).showToast();
                </script>
            @endif

            @if ($errors->any())
                <div class="p-2 mb-4 text-red-800 bg-red-200 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



            {{-- Legend  --}}
            <div class="flex w-full gap-4 mt-4 mb-6">
                <div onclick="history.back()" class="flex gap-2 text-xl hover:text-gray-600">
                    <div> <i class="fa-solid fa-backward"></i></div>
                    <div>Back</div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 bg-yellow-500 rounded"></div> <span>Premium ($9.78)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 bg-green-500 rounded"></div> <span>Standard ($6.23)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 bg-red-500 rounded"></div> <span>Booked</span>
                </div>
            </div>

            {{-- Seat Assign --}}
            <form method="post" action="{{ url('/flight/assign-seat') }}" class="w-full">
                @csrf

                @php
                    $leftCols = ['A', 'B', 'C'];
                    $rightCols = ['D', 'E', 'F'];
                    $groupedSeats = [];

                    foreach ($allSeats as $seat) {
                        $row = intval($seat);
                        $groupedSeats[$row][] = $seat;
                    }
                @endphp

                {{-- selecting seats --}}
                <div class="flex flex-col md:flex-row gap-5 w-full min-h-[60vh]">
                    <div class="space-y-3 overflow-y-auto  h-[400px] md:h-auto  rounded p-4 bg-white max-h-[80vh]">
                        {{-- select seats --}}
                        @foreach ($groupedSeats as $rowNum => $rowSeats)
                            <div class="flex items-center justify-center gap-6 ">
                                {{-- Left side --}}
                                <div class="flex gap-2">
                                    @foreach ($rowSeats as $seat)
                                        @php
                                            $col = substr($seat, -1);
                                            if (!in_array($col, $leftCols)) {
                                                continue;
                                            }

                                            $price = $rowNum <= 14 ? 9.78 : 6.23;
                                            $booked = in_array($seat, $bookedSeats);
                                        @endphp
                                        <label class="flex items-center justify-center w-10 h-10 cursor-pointer">
                                            {{-- Radio button hidden but still functional --}}
                                            <input type="radio" name="selected_seat" value="{{ $seat }}"
                                                class="sr-only peer" required>

                                            {{-- Custom square box --}}
                                            <div
                                                class="w-full h-full rounded flex items-center justify-center font-bold text-white
                                              {{ $booked ? 'bg-red-500 cursor-not-allowed' : ($price === 9.78 ? 'bg-yellow-500' : 'bg-green-500') }}
                                             peer-checked:bg-blue-600 peer-checked:border-blue-600">
                                                @if ($booked)
                                                    X
                                                @else
                                                    {{ $seat }}
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                {{-- Aisle --}}
                                <div class="w-6"></div>

                                {{-- Right side --}}
                                <div class="flex justify-center gap-5">
                                    @foreach ($rowSeats as $seat)
                                        @php
                                            $col = substr($seat, -1);
                                            if (!in_array($col, $rightCols)) {
                                                continue;
                                            }

                                            $price = $rowNum <= 14 ? 9.78 : 6.23;
                                            $booked = in_array($seat, $bookedSeats);
                                        @endphp
                                        <label class="flex items-center justify-center w-10 h-10 cursor-pointer">
                                            {{-- Radio button hidden but still functional --}}
                                            <input type="radio" name="selected_seat" value="{{ $seat }}"
                                                class="sr-only peer" required>

                                            {{-- Custom square box --}}
                                            <div
                                                class="w-full h-full rounded flex items-center justify-center font-bold text-white
                                                {{ $booked ? 'bg-red-500 cursor-not-allowed' : ($price === 9.78 ? 'bg-yellow-500' : 'bg-green-500') }}
                                                 peer-checked:bg-blue-600 peer-checked:border-blue-600">
                                                @if ($booked)
                                                    X
                                                @else
                                                    {{ $seat }}
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        {{-- end select seats --}}
                    </div>


                    {{-- table  --}}
                    <div class="overflow-auto flex-1 max-h-[400px] md:max-h-none bg-white shadow rounded p-4">
                        <table class="min-w-full overflow-y-auto table-auto">
                            <thead class="text-sm text-white uppercase bg-blue-400 -z-10">
                                <tr>
                                    <th class="px-3 py-4 text-left"></th>
                                    <th class="px-3 py-4 text-left">Passenger Name</th>
                                    <th class="px-4 py-3 text-left">ID Number</th>
                                    <th class="px-4 py-3 text-left">Birthday</th>
                                    <th class="px-4 py-3 text-left">Gender</th>
                                    <th class="px-4 py-3 text-left">Assign Seat</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700" id="flightTableBody">
                                @if (count($assignedSeat) > 0)
                                    @foreach ($assignedSeat as $flight)
                                        <input type="hidden" name="flight_id" value="{{ $flight->id }}">
                                        <input type="hidden" name="first_name"
                                            value="{{ $flight->passenger_first_name }}">
                                        <input type="hidden" name="last_name" value="{{ $flight->passenger_last_name }}">

                                        <tr
                                            class="items-center transition duration-200 border-b border-gray-300 hover:bg-zinc-200">
                                            {{-- checkbox --}}
                                            <td class="px-4 py-4">
                                                <input type="checkbox" name="selected_user" value="{{ $flight->user_id }}"
                                                    class="single-select" />
                                            </td>
                                            <!-- Passenger Name -->
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ $flight->passenger_first_name }}
                                                {{ $flight->passenger_last_name }}</td>

                                            <!-- ID Number -->
                                            <td class="px-4 py-4 text-sm text-gray-600">-</td>

                                            <!-- Birthday -->
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ $flight->birthday }}</td>

                                            <!-- Gender -->
                                            <td class="px-4 py-4 text-sm"><span
                                                    class="font-medium">{{ $flight->gender }}</span>
                                            </td>

                                            <!-- Assign Seat -->
                                            <td class="px-5 py-4 font-bold text-md">
                                                <button type="submit" class="p-3 text-white bg-blue-500 rounded-lg">
                                                    Assign Seat
                                                </button>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="px-4 py-4 text-center text-gray-400">
                                            There is no passenger to assign seat!
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {{-- table end --}}
                </div>
            </form>

        </div>
    </div>

@endsection

@section('script')
    <script>
        // selected User
        document.querySelectorAll('.single-select').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    // Uncheck all others
                    document.querySelectorAll('.single-select').forEach(other => {
                        if (other !== checkbox) {
                            other.checked = false;
                        }
                    });
                }
            });
        });
    </script>
@endsection
