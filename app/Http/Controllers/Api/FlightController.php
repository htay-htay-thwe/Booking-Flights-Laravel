<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Flight;
use App\Models\Reserve;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightController extends Controller
{
    // get Airport data
    public function getAirports(Request $request)
    {
        $search   = strtolower($request->query('search', ''));
        $path     = base_path('resources/data/airports.json');
        $airports = json_decode(file_get_contents($path), true);

        // If no search query, return first 6 or 7 airports as default
        if (empty($search)) {
            $result = array_slice($airports, 0, 35);
        } else {
            // Filter airports by search text (name, iata, city)

            $filtered = array_filter($airports, function ($airport) use ($search) {
                return stripos($airport['name'], $search) !== false ||
                (isset($airport['iata']) && stripos($airport['iata'], $search) !== false) ||
                stripos($airport['city'], $search) !== false;
            });
            $result = array_slice($filtered, 0, 35); // limit results
        }

        return response()->json(array_values($result));
    }

    // search Airports
    public function searchAirports(Request $request)
    {

        if ($request->tripType == 'oneway') {
            $flights = Flight::where('from', $request->from)
                ->where('to', $request->to)
                ->where('departure_date', $request->departure_date)
                ->orderBy('departure_date', 'asc')
                ->get()
                ->toArray();
            if (! empty($flights)) {
                $flightData = $this->encodeJson($flights);

            } else {
                $flightData = [];
            }

            return response()->json([
                'tripType' => 'oneway',
                'flights'  => $flightData,
            ], 200);

        } elseif ($request->tripType == 'round') {
            // Outbound flights
            $outboundFlights = Flight::where('from', $request->from)
                ->where('to', $request->to)
                ->where('departure_date', $request->departure_date)
                ->orderBy('departure_date', 'asc')
                ->get()
                ->toArray();

            // Return flights (reverse route)
            $returnFlights = Flight::where('from', $request->to)
                ->where('to', $request->from)
                ->where('departure_date', $request->return_date)
                ->orderBy('departure_date', 'asc')
                ->get()
                ->toArray();

            if (! empty($outboundFlights)) {
                $encodedOutbound = $this->encodeJson($outboundFlights);
            } else {
                $encodedOutbound = [];
            }
            if (! empty($returnFlights)) {
                $encodedReturn = $this->encodeJson($returnFlights);
            } else {
                $encodedReturn = [];
            }

            return response()->json([
                'tripType' => 'round',
                'outbound' => $encodedOutbound,
                'return'   => $encodedReturn,
            ], 200);
        }

        // Default response if nothing matched
        return response()->json([], 200);
    }

    // flight Info of payment
    public function flightInfo($id)
    {
        $uuid = Cart::where(function ($query) use ($id) {
            $query->where('id', $id)
                ->orWhere('uuid', $id);
        })->value('uuid');

        $uuid = $uuid ?: $id;

        $flightIds = DB::table('carts')->where('uuid', $uuid)->pluck('flight_id')->toArray();

        if (is_array($flightIds) && isset($flightIds[1])) {
            $flights = [];
            foreach ($flightIds as $flight_id) {
                $flight = Flight::where('id', $flight_id)->first();

                if ($flight) {
                    $flights[] = $flight->toArray();
                }
            }
        } else {
            $flightId = Cart::orWhere('id', $id)->orWhere('uuid', $id)->value('flight_id');
            $flights  = Flight::where('id', $flightId)->first()->toArray();
        }

        if (! empty($flights)) {
            $flightData = $this->encodeJson($flights);
        } else {
            // If no flights, just return empty array or any default value you want
            $flightData = [];
        }

        return response()->json($flightData, 200);
    }

    public function flightInfoDirect(Request $request)
    {
        if (! empty($request->outbound)) {
            $flightIds    = [];
            $flightIds[0] = $request->outbound;
            $flightIds[1] = $request->flightId;

            foreach ($flightIds as $flight_id) {
                $flight = Flight::where('id', $flight_id)->first();

                if ($flight) {
                    $flights[] = $flight->toArray();
                }
            }

        } else {
            $flights = Flight::where('id', $request->flightId)->first()->toArray();
        }

        if (! empty($flights)) {
            $flightData = $this->encodeJson($flights);
        } else {
            // If no flights, just return empty array or any default value you want
            $flightData = [];
        }
        return response()->json($flightData, 200);
    }

    public function getBookings($id)
    {
        $flightIds = Reserve::where('user_id', $id)->where('paymentStatus', 'paid')->pluck('flight_id');
        $flightIds = $flightIds->toArray();

        if (is_array($flightIds) && isset($flightIds[1])) {
            $flights = [];
            foreach ($flightIds as $flight_id) {
                $flight = Flight::where('id', $flight_id)->first();

                if ($flight) {
                    $flights[] = $flight->toArray();
                }
            }
        } else {
            $flights = Flight::where('id', $flightIds)->first();
        }

        if (! empty($flights)) {
            $today = Carbon::today();

            $filteredFlights = collect($flights)->filter(function ($flight) use ($today) {
                return ! empty($flight['departure_date']) && Carbon::parse($flight['departure_date'])->greaterThanOrEqualTo($today);
            })->values()->all();

            $flightData = $this->encodeJson($filteredFlights);
        } else {
            // If no flights, just return empty array or any default value you want
            $flightData = [];
        }

        return response()->json($flightData, 200);

    }

    public function getSeats($id)
    {
        $reservedSeats = Reserve::where('flight_id', $id)
            ->whereNotNull('seat')
            ->pluck('seat')
            ->toArray();
        return response()->json($reservedSeats, 200);

    }

    public function getSeatsArray($outboundId, $id)
    {
        $first = Reserve::where('flight_id', $outboundId)
            ->whereNotNull('seat')
            ->pluck('seat')
            ->toArray();
        $second = Reserve::where('flight_id', $id)
            ->whereNotNull('seat')
            ->pluck('seat')
            ->toArray();
        $reservedSeats = [
            $first,
            $second,
        ];

        logger($reservedSeats);
        return response()->json($reservedSeats, 200);

    }

    // flights lists
    private function encodeJson($flights)
    {

        $path     = base_path('resources/data/airports.json');
        $airports = json_decode(file_get_contents($path), true);

        $j        = base_path('resources/data/airlines.json');
        $airlines = json_decode(file_get_contents($j), true);

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

}
