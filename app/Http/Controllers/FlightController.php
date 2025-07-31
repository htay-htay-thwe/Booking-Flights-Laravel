<?php
namespace App\Http\Controllers;

use App\Models\Cancel;
use App\Models\Flight;
use App\Models\Reserve;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class FlightController extends Controller
{
    // create flight page
    public function createFlightPage()
    {
        return view('flight.create');
    }

    // create flight
    public function createFlight(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "airline"        => 'required',
            "from"           => 'required',
            "to"             => 'required',
            "departure_date" => 'required',
            "fromTime"       => 'required',
            "toTime"         => 'required',
            "price"          => 'required',
            "flightStatus"   => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/flight/create/page')
                ->withErrors($validator)
                ->withInput();
        }

        Flight::create([
            "airline"        => $request->airline,
            "from"           => $request->from,
            "to"             => $request->to,
            "departure_date" => $request->departure_date,
            "fromTime"       => $request->fromTime,
            "toTime"         => $request->toTime,
            "price"          => $request->price,
            "flightStatus"   => $request->flightStatus,
        ]);

        return redirect()->route('list#page')->with('success', "Flight Created!");
    }

    // flight list Page
    public function listPage(Request $request)
    {
        $flights = Flight::all()->toArray();

        if (! empty($flights)) {
            $flightData = $this->encodeJson($flights);

        } else {
            $flightData = [];
        }

        $pagination = $this->pagination($request, $flightData);

        return view('flight.lists', [
            'pagination' => $pagination,
        ]);
    }

    // delete daily flights
    public function deleteFlights($id)
    {
        Flight::where('id', $id)->delete();
        return redirect()->back()->with('delete', "Deleted!");
    }

    // flight edit
    public function editFlights($id)
    {
        $flight = Flight::where('id', $id)->first();
        return view('flight.edit', compact('flight'));
    }

    // flight update
    public function updateFlights(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "airline"        => 'required',
            "from"           => 'required',
            "to"             => 'required',
            "departure_date" => 'required',
            "fromTime"       => 'required',
            "toTime"         => 'required',
            "price"          => 'required',
            "flightStatus"   => 'required',
        ]);
        if ($validator->fails()) {
            $id = $request->id ?? $request->route('id'); // try both

            return redirect()->route('flights#edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        Flight::where('id', $request->id)->update([
            "airline"        => $request->airline,
            "from"           => $request->from,
            "to"             => $request->to,
            "departure_date" => $request->departure_date,
            "fromTime"       => $request->fromTime,
            "toTime"         => $request->toTime,
            "price"          => $request->price,
            "flightStatus"   => $request->flightStatus,
        ]);
        return redirect()->route('list#page')->with('success', "Flight Updated!");
    }

    // flight Ajax Sort
    public function ajaxSort(Request $request)
    {
        $sort  = $request->get('sort');
        $query = Flight::all();

        // Convert flights to array, then add duration field
        $flightsWithDuration = $query->map(function ($flight) {
            $from = \Carbon\Carbon::createFromFormat('H:i', $flight->fromTime);
            $to   = \Carbon\Carbon::createFromFormat('H:i', $flight->toTime);

            if ($to->lessThan($from)) {
                $to->addDay();
            }

            $diff     = $to->diff($from);
            $duration = sprintf('%dh %02dm', $diff->h, $diff->i);

            // Add duration as a field
            $flightArray             = $flight->toArray();
            $flightArray['duration'] = $duration;

            return $flightArray;
        });

        $durationToMinutes = function ($duration) {
            preg_match('/(\d+)h\s*(\d+)m/', $duration, $matches);
            $hours   = isset($matches[1]) ? (int) $matches[1] : 0;
            $minutes = isset($matches[2]) ? (int) $matches[2] : 0;
            return $hours * 60 + $minutes;
        };

        if ($sort === 'price_asc') {
            $flightsWithDuration = $flightsWithDuration->sortBy(fn($item) => (float) $item['price'])->values();
        } elseif ($sort === 'price_desc') {
            $flightsWithDuration = $flightsWithDuration->sortByDesc(fn($item) => (float) $item['price'])->values();
        } elseif ($sort === 'duration_asc') {
            $flightsWithDuration = $flightsWithDuration->sortBy(fn($item) => $durationToMinutes($item['duration']))->values();
        } elseif ($sort === 'duration_desc') {
            $flightsWithDuration = $flightsWithDuration->sortByDesc(fn($item) => $durationToMinutes($item['duration']))->values();
        }

        // paginate your sorted flights
        $pagination = $this->pagination($request, $flightsWithDuration);

        // Get current page items (array)
        $pageItems = $pagination->items();

        // Encode only the current page items (if needed)
        $encodedItems = $this->encodeJson($pageItems);

        // Replace paginator collection with encoded items
        $pagination->setCollection(collect($encodedItems));

        return response()->json($pagination);
    }

    // flight search
    public function liveSearch(Request $request)
    {
        $search     = strtolower($request->get('query', '')); // make search case-insensitive
        $query      = Flight::all()->toArray();
        $flightData = collect($this->encodeJson($query));

        // Filter flights based on airline, from, to, FromCity, ToCity, etc.
        $filtered = $flightData->filter(function ($flight) use ($search) {
            return str_contains(strtolower($flight['airline']), $search)
            || str_contains(strtolower($flight['from']), $search)
            || str_contains(strtolower($flight['to']), $search)
            || str_contains(strtolower($flight['FromCity']), $search)
            || str_contains(strtolower($flight['ToCity']), $search)
            || str_contains(strtolower($flight['airport_code_from']), $search)
            || str_contains(strtolower($flight['airport_code_to']), $search)
            || str_contains(strtolower($flight['departure_date']), $search)
            || str_contains(strtolower($flight['price']), $search)
            || str_contains(strtolower($flight['flightStatus']), $search)
            || str_contains(strtolower($flight['toTime']), $search)
            || str_contains(strtolower($flight['fromTime']), $search);
        })->values(); // reset keys

        // paginate your sorted flights
        $pagination = $this->pagination($request, $filtered);

        // Get current page items (array)
        $pageItems = $pagination->items();

        // Replace paginator collection with encoded items
        $pagination->setCollection(collect($pageItems));

        return response()->json($pagination);

    }

    // flight detail Page
    public function detailPage($id)
    {
        $data            = Flight::where('id', $id)->first();
        $flight          = $this->encodeJson($data->toArray());
        $reserve         = Reserve::where('flight_id', $id)->paginate(10);
        $cancel          = Cancel::where('flight_id', $id)->get();
        $bookedSeatCount = collect($reserve)->whereNotNull('seat')->count();
        $confirmedCount  = $reserve->where('bookStatus', 'confirmed')->count();
        $canceledCount   = $cancel->where('bookStatus', 'cancel')->count();
        $seatCollection  = $this->seatNumber();
        return view('flight.details', compact('flight', 'reserve', 'bookedSeatCount', 'seatCollection', 'confirmedCount', 'canceledCount'));
    }

    // update checkin
    public function updateCheckIn($id)
    {
        Reserve::where('id', $id)->update([
            'checkInStatus' => 'checkIn',
        ]);
        return back()->with('checkIn', "CheckIn!");
    }
    // update booking status
    public function updateBook($id)
    {
        Reserve::where('id', $id)->update([
            'bookStatus' => 'confirmed',
        ]);
        return back()->with('emailSent', "Booking Confirmed");
    }

    // update booking status
    public function cancelBooking($id)
    {
        $reserve = Reserve::findOrFail($id);
        Cancel::create($reserve->toArray());
        Reserve::where('id', $id)->update([
            'bookStatus' => 'cancel',
        ]);
        // Then delete from reserve
        $reserve->delete();

        return back()->with('cancel', "Booking canceled!");

    }

    // custom pagination
    private function pagination($request, $flightData)
    {

        $page    = $request->input('page', 1);
        $perPage = 10;

        $items            = collect($flightData);
        $currentPageItems = $items->slice(($page - 1) * $perPage, $perPage)->values();
        $flightPagination = new LengthAwarePaginator(
            $currentPageItems,
            $items->count(),
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return $flightPagination;

    }

    // flights lists
    private function encodeJson($flights)
    {

        $path     = base_path('resources/data/airports.json');
        $airports = json_decode(file_get_contents($path));

        $j     = base_path('resources/data/airlines.json');
        $airlines = json_decode(file_get_contents($j));

        if (is_array($flights) && isset($flights[0])) {
            // Multiple flights
            return collect($flights)->map(function ($flight) use ($airlines, $airports) {
                return $this->encodeData($flight, $airlines, $airports);
            })->toArray();
        } elseif (is_array($flights)) {
            // Single flight
            $flight = $flights;
            return $this->encodeData($flight, $airlines, $airports);
        }
        return $flights;

    }

    // separate encode functions
    private function encodeData($flights, $airlines, $airports)
    {
        $match        = $airlines->firstWhere('id', $flights['airline']);
        $matchAirFrom = $airports->firstWhere('icao', $flights['from']);
        $matchAirTo   = $airports->firstWhere('icao', $flights['to']);
        $from         = Carbon::createFromFormat('H:i', $flights['fromTime']);
        $to           = Carbon::createFromFormat('H:i', $flights['toTime']);

        if ($to->lessThan($from)) {
            $to->addDay();
        }

        $diff = $to->diff($from);

        $flights['duration']          = sprintf('%dh %02dm', $diff->h, $diff->i);
        $flights['airline']           = $match['name'] ?? 'Unknown Airline';
        $flights['airline_logo']      = $match['logo'] ?? null;
        $flights['from']              = $matchAirFrom['name'] ?? null;
        $flights['FromCity']          = $matchAirFrom['city'] ?? null;
        $flights['airport_code_from'] = $matchAirFrom['iata'] ?? null;
        $flights['to']                = $matchAirTo['name'] ?? null;
        $flights['ToCity']            = $matchAirTo['city'] ?? null;
        $flights['airport_code_to']   = $matchAirTo['iata'] ?? null;

        return $flights;

    }

    //  seat management
    private function seatNumber()
    {
        $rows    = range(1, 31);
        $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
        $seats   = [];
        foreach ($rows as $row) {
            foreach ($columns as $col) {
                $seats[] = $row . $col;
            }
        }
        return $seats;
    }

}
