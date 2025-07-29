@extends('layouts.app')

@section('content')
    {{-- mainContent --}}
    <div class="flex-1 mt-16 overflow-y-auto bg-gray-100 md:mt-0">

        <div class="pt-16 bg-gray-800">
            <div class="p-4 text-2xl text-white shadow rounded-tl-3xl bg-gradient-to-r from-blue-900 to-gray-800">
                <h3 class="pl-2 font-bold">Analytics</h3>
            </div>
        </div>

        <div class="flex flex-wrap">
            <div class="w-full p-6 md:w-1/2 xl:w-1/3">
                <!--Metric Card-->
                <div
                    class="p-5 border-b-4 border-green-600 rounded-lg shadow-xl bg-gradient-to-b from-green-200 to-green-100">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="p-5 bg-green-600 rounded-full"><i class="fa fa-wallet fa-2x fa-inverse"></i>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold text-gray-600 uppercase">Total Revenue</h5>
                            <h3 class="text-3xl font-bold">${{ $totalYearlyRevenue }} <span class="text-green-500"><i
                                        class="fas fa-caret-up"></i></span></h3>
                        </div>
                    </div>
                </div>
                <!--/Metric Card-->
            </div>
            <div class="w-full p-6 md:w-1/2 xl:w-1/3">
                <!--Metric Card-->
                <div class="p-5 border-b-4 border-pink-500 rounded-lg shadow-xl bg-gradient-to-b from-pink-200 to-pink-100">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="p-5 bg-pink-600 rounded-full"><i class="fas fa-users fa-2x fa-inverse"></i>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold text-gray-600 uppercase">Total Users</h5>
                            <h3 class="text-3xl font-bold">{{ $userCount }} <span class="text-pink-500"><i
                                        class="fas fa-exchange-alt"></i></span></h3>
                        </div>
                    </div>
                </div>
                <!--/Metric Card-->
            </div>
            <div class="w-full p-6 md:w-1/2 xl:w-1/3">
                <!--Metric Card-->
                <div
                    class="p-5 border-b-4 border-yellow-600 rounded-lg shadow-xl bg-gradient-to-b from-yellow-200 to-yellow-100">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="p-5 bg-yellow-600 rounded-full"><i class="fas fa-user-plus fa-2x fa-inverse"></i>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold text-gray-600 uppercase">New Users</h5>
                            <h3 class="text-3xl font-bold">{{ $newUserCount }} <span class="text-yellow-600"><i
                                        class="fas fa-caret-up"></i></span></h3>
                        </div>
                    </div>
                </div>
                <!--/Metric Card-->
            </div>
            <div class="w-full p-6 md:w-1/2 xl:w-1/3">
                <!--Metric Card-->
                <div class="p-5 border-b-4 border-blue-500 rounded-lg shadow-xl bg-gradient-to-b from-blue-200 to-blue-100">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="p-5 bg-blue-600 rounded-full"><i class="fas fa-server fa-2x fa-inverse"></i>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold text-gray-600 uppercase">Server Uptime</h5>
                            <h3 class="text-3xl font-bold">152 days</h3>
                        </div>
                    </div>
                </div>
                <!--/Metric Card-->
            </div>
            <div class="w-full p-6 md:w-1/2 xl:w-1/3">
                <!--Metric Card-->
                <div
                    class="p-5 border-b-4 border-indigo-500 rounded-lg shadow-xl bg-gradient-to-b from-indigo-200 to-indigo-100">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="p-5 bg-indigo-600 rounded-full"><i class="fas fa-tasks fa-2x fa-inverse"></i></div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold text-gray-600 uppercase">To Do List</h5>
                            <h3 class="text-3xl font-bold">7 tasks</h3>
                        </div>
                    </div>
                </div>
                <!--/Metric Card-->
            </div>
            <div class="w-full p-6 md:w-1/2 xl:w-1/3">
                <!--Metric Card-->
                <div class="p-5 border-b-4 border-red-500 rounded-lg shadow-xl bg-gradient-to-b from-red-200 to-red-100">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="p-5 bg-red-600 rounded-full"><i class="fas fa-inbox fa-2x fa-inverse"></i>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold text-gray-600 uppercase">Issues</h5>
                            <h3 class="text-3xl font-bold">3 <span class="text-red-500"><i
                                        class="fas fa-caret-up"></i></span></h3>
                        </div>
                    </div>
                </div>
                <!--/Metric Card-->
            </div>
        </div>


        <div class="flex flex-row flex-wrap flex-grow mt-2">

            <div class="w-full p-6 md:w-full xl:w-1/2">
                <!--Graph Card-->
                <div class="bg-white border-transparent rounded-lg shadow-xl">
                    <div
                        class="p-2 text-gray-800 uppercase border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg bg-gradient-to-b from-gray-300 to-gray-100">
                        <h5 class="font-bold text-gray-600 uppercase">Total Bookings per Month - Graph</h5>
                    </div>
                    <div class="p-5">
                        <canvas id="chartjs-7" class="chartjs" width="undefined" height="undefined"></canvas>
                        <script>
                            const bookingData = @json($bookingsPerMonth); // From Laravel
                            const monthLabels = [
                                "January", "February", "March", "April",
                                "May", "June", "July", "August",
                                "September", "October", "November", "December"
                            ];
                            new Chart(document.getElementById("chartjs-7"), {
                                "type": "bar",
                                "data": {
                                    "labels": monthLabels,
                                    "datasets": [{
                                            "label": "Bookings",
                                            "data": bookingData,
                                            "borderColor": "rgb(255, 99, 132)",
                                            "backgroundColor": "rgba(255, 99, 132, 0.2)"
                                        },
                                        // {
                                        //     "label": "Adsense Clicks",
                                        //     "data": [5, 15, 10, 30],
                                        //     "type": "line",
                                        //     "fill": false,
                                        //     "borderColor": "rgb(54, 162, 235)"
                                        // }
                                    ]
                                },
                                "options": {
                                    "scales": {
                                        "yAxes": [{
                                            "ticks": {
                                                "beginAtZero": true,
                                                "stepSize": 5,
                                                "max": 50
                                            },
                                        }]
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
                <!--/Graph Card-->
            </div>

            <div class="w-full p-6 md:w-full xl:w-1/2">
                <!--Graph Card-->
                <div class="bg-white border-transparent rounded-lg shadow-xl">
                    <div
                        class="p-2 text-gray-800 uppercase border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg bg-gradient-to-b from-gray-300 to-gray-100">
                        <h5 class="font-bold text-gray-600 uppercase">Total Revenue per Month (USD) - Graph</h5>
                    </div>
                    <div class="p-5">
                        <canvas id="chartjs-0" class="chartjs" width="undefined" height="undefined"></canvas>
                        <script>
                            const revenueData = @json($revenuePerMonth);
                            new Chart(document.getElementById("chartjs-0"), {
                                "type": "line",
                                "data": {
                                    "labels": monthLabels,
                                    "datasets": [{
                                        "label": "Total Revenue",
                                        "data": revenueData,
                                        "fill": false,
                                        "borderColor": "rgb(75, 192, 192)",
                                        "lineTension": 0.1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true,
                                                stepSize: 1000, // ✅ Adjust step size here
                                                max: 10000 // ✅ Set max value here
                                            }
                                        }]
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
                <!--/Graph Card-->
            </div>

            <div class="w-full p-6 md:w-full xl:w-1/2">
                <!--Graph Card-->
                <div class="bg-white border-transparent rounded-lg shadow-xl">
                    <div
                        class="p-2 text-gray-800 uppercase border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg bg-gradient-to-b from-gray-300 to-gray-100">
                        <h5 class="font-bold text-gray-600 uppercase">User Increased per Month - Graph</h5>
                    </div>
                    <div class="p-5">
                        <canvas id="chartjs-1" class="chartjs" width="undefined" height="undefined"></canvas>
                        <script>
                            const counts = @json($usersPerMonth);

                            new Chart(document.getElementById("chartjs-1"), {
                                type: "bar",
                                data: {
                                    labels: monthLabels,
                                    datasets: [{
                                        label: "Users Created",
                                        data: counts,
                                        fill: false,
                                        backgroundColor: [
                                            "rgba(75, 192, 192, 0.2)", "rgba(255, 99, 132, 0.2)",
                                            "rgba(255, 205, 86, 0.2)", "rgba(153, 102, 255, 0.2)",
                                            "rgba(54, 162, 235, 0.2)", "rgba(255, 159, 64, 0.2)",
                                            "rgba(201, 203, 207, 0.2)", "rgba(104, 132, 245, 0.2)",
                                            "rgba(245, 104, 132, 0.2)", "rgba(132, 245, 104, 0.2)",
                                            "rgba(104, 245, 132, 0.2)", "rgba(245, 132, 104, 0.2)"
                                        ],
                                        borderColor: "rgb(75, 192, 192)",
                                        borderWidth: 1
                                    }]
                                },
                                "options": {
                                    "scales": {
                                        "yAxes": [{
                                            "ticks": {
                                                "beginAtZero": true,
                                                "stepSize": 5,
                                                "max": 50
                                            },
                                        }]
                                    }
                                }
                            });
                        </script>

                    </div>
                </div>
                <!--/Graph Card-->
            </div>

            <div class="w-full p-6 md:w-full xl:w-1/2">
                <!--Graph Card-->
                <div class="bg-white border-transparent rounded-lg shadow-xl">
                    <div
                        class="p-2 text-gray-800 uppercase border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg bg-gradient-to-b from-gray-300 to-gray-100">
                        <h5 class="font-bold text-gray-600 uppercase">Flight Status throughout the year - Graph</h5>
                    </div>
                    <div class="p-5"><canvas id="chartjs-4" class="chartjs" width="undefined"
                            height="undefined"></canvas>
                        <script>
                            const flightStatusCounts = @json(array_values($flightStatusCounts));
                            const flightStatusLabels = @json(array_keys($flightStatusCounts));

                            new Chart(document.getElementById("chartjs-4"), {
                                "type": "doughnut",
                                "data": {
                                    "labels": flightStatusLabels,
                                    "datasets": [{
                                        "label": "Issues",
                                        "data": flightStatusCounts,
                                        "backgroundColor": ["rgb(255, 99, 132)", "rgb(255, 205, 86)", "rgb(54, 162, 235)"]
                                    }]
                                }
                            });
                        </script>
                    </div>
                </div>
                <!--/Graph Card-->
            </div>

            <div class="w-full p-6 md:w-full xl:w-1/2">
                <!--Table Card-->
                <div class="bg-white border-transparent rounded-lg shadow-xl">
                    <div
                        class="p-2 text-gray-800 uppercase border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg bg-gradient-to-b from-gray-300 to-gray-100">
                        <h5 class="font-bold text-gray-600 uppercase">Admins</h5>
                    </div>
                    <div class="p-5">
                        <table class="w-full p-5 text-gray-700">
                            <thead>
                                <tr>
                                    <th class="text-left text-blue-900">Name</th>
                                    <th class="text-left text-blue-900">Role</th>
                                    <th class="text-left text-blue-900">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($admin as $ad)
                                    <tr>
                                        <td class="py-3">{{ $ad['name'] }}</td>
                                        <td class="py-3">{{ $ad['role'] }}</td>
                                        <td class="py-3">
                                            @if (Auth::user()->id !== $ad['id'])
                                                <a href="/admin/delete/{{ $ad['id'] }}"
                                                    class="text-xl text-red-600 hover:text-red-800">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--/table Card-->
            </div>

            {{-- <div class="w-full p-6 md:w-full xl:w-1/2">
                <!--Advert Card-->
                <div class="bg-white border-transparent rounded-lg shadow-xl">
                    <div
                        class="p-2 text-gray-800 uppercase border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg bg-gradient-to-b from-gray-300 to-gray-100">
                        <h5 class="font-bold text-gray-600 uppercase">Advert</h5>
                    </div>
                    <div class="p-5 text-center">


                        <script async type="text/javascript" src="//cdn.carbonads.com/carbon.js?serve=CK7D52JJ&placement=wwwtailwindtoolboxcom"
                            id="_carbonads_js"></script>


                    </div>
                </div>
                <!--/Advert Card-->
            </div> --}}


        </div>
        {{-- <div>
            <p class="px-6 font-bold text-gray-600">Distributed By: <a href="https://themewagon.com/"
                    target="_blank">ThemeWagon</a></p>
        </div> --}}
    </div>

    <script>
        /*Toggle dropdown list*/
        function toggleDD(myDropMenu) {
            document.getElementById(myDropMenu).classList.toggle("invisible");
        }
        /*Filter dropdown options*/
        function filterDD(myDropMenu, myDropMenuSearch) {
            var input, filter, ul, li, a, i;
            input = document.getElementById(myDropMenuSearch);
            filter = input.value.toUpperCase();
            div = document.getElementById(myDropMenu);
            a = div.getElementsByTagName("a");
            for (i = 0; i < a.length; i++) {
                if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }
        }
        // Close the dropdown menu if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.drop-button') && !event.target.matches('.drop-search')) {
                var dropdowns = document.getElementsByClassName("dropdownlist");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (!openDropdown.classList.contains('invisible')) {
                        openDropdown.classList.add('invisible');
                    }
                }
            }
        }
    </script>
    {{-- mainContent end --}}
@endsection

@section('script')
    <script>
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
    </script>
@endsection
