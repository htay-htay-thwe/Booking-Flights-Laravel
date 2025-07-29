<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CartController extends Controller
{
    // add carts to db
    public function addCart($flight_id, $user_id)
    {
        $cartOne = Cart::create([
            'user_id'   => $user_id,
            'flight_id' => $flight_id,
            'uuid'      => (string) Str::uuid(), // store uuid here
        ]);

        $flights = $this->mergedData($user_id);
        if (! empty($flights)) {
            $cartData = ! empty($flights) ? $this->encodeJson($flights) : [];

        } else {
            $cartData = [];
        }
        logger('cartOne' . $cartOne);
        return response()->json([
            'cartData' => $cartData,
            'cartOne'  => $cartOne,
        ], 200);
    }

    // round trip add cart
    public function addCartMultiple(Request $request)
    {
        $user_id    = $request->input('user_id');
        $flight_ids = $request->input('flight_ids', []);
        $uuid       = $request->input('uuid') ?? Str::uuid()->toString();

        // Insert all flights with the same uuid
        foreach ($flight_ids as $flight_id) {
            $cartOne = Cart::create([
                'user_id'   => $user_id,
                'flight_id' => $flight_id,
                'uuid'      => $uuid,
            ]);
        }

        $flights = $this->mergedData($user_id);
        if (! empty($flights)) {
            $cartData = ! empty($flights) ? $this->encodeJson($flights) : [];

        } else {
            $cartData = [];
        }

        return response()->json([
            'cartData' => $cartData,
            'cartOne'  => $cartOne,
        ], 200);

    }

    // remove user cart
    public function removeCart($cart_id, $user_id)
    {
        $uuid = Cart::where('id', $cart_id)->pluck('uuid');
        logger($uuid);

        $cartIds = Cart::where('uuid', $uuid)->pluck('id')->toArray();

        if (is_array($cartIds) && isset($cartIds[1])) {
            foreach ($cartIds as $cart_id) {
                Cart::where('id', $cart_id)->delete();
            }
        } else {
            Cart::where('id', $cart_id)->delete();
        }
        $flights = $this->mergedData($user_id);
        if (! empty($flights)) {
            $cartData = $this->encodeJson($flights);
        } else {
            $cartData = [];
        }

        return response()->json($cartData, 200);

    }

    // get Carts
    public function getCarts($user_id)
    {
        $flights = $this->mergedData($user_id);
        if (! empty($flights)) {
            $cartData = $this->encodeJson($flights);
        } else {
            $cartData = [];
        }
        return response()->json($cartData, 200);
    }

    // data merged
    private function mergedData($user_id)
    {
        $flights = DB::table('carts')
            ->join('flights', 'carts.flight_id', '=', 'flights.id')
            ->where('carts.user_id', $user_id)
            ->select(
                'carts.id as id',
                'carts.user_id',
                'carts.flight_id',
                'carts.uuid',
                'flights.airline',
                'flights.from',
                'flights.to',
                'flights.price',
                'flights.departure_date',
                'flights.fromTime',
                'flights.toTime'
            )
            ->get()
            ->map(fn($item) => (array) $item)
            ->groupBy('uuid')
            ->map(function ($group, $uuid) {
                $groupArray = $group->values()->toArray();
                // If multiple flights share UUID, return array of flights as is
                if (count($groupArray) > 1) {
                    return $groupArray;
                }
                // If single flight, return flight object (with uuid inside flight data)
                return $groupArray[0];
            })
            ->values()
            ->toArray();

        return $flights;
    }

    private function encodeJson($flights)
    {
        $json     = Storage::get('airports.json');
        $airports = collect(json_decode($json, true));
        $j        = Storage::get('airlines.json');
        $airlines = collect(json_decode($j, true));

        return collect($flights)->map(function ($item) use ($airlines, $airports) {
            if (is_array($item) && isset($item[0])) {
                // group of flights
                return collect($item)->map(fn($flight) => $this->encodeData($flight, $airlines, $airports))->toArray();
            }
            // single flight object
            return $this->encodeData($item, $airlines, $airports);
        })->toArray();
    }

    // Helper to enrich flight data with airport and airline info and duration
    private function encodeData(array $flight, $airlines, $airports): array
    {
        $matchAirline = $airlines->firstWhere('id', $flight['airline']);
        $matchAirFrom = $airports->firstWhere('icao', $flight['from']);
        $matchAirTo   = $airports->firstWhere('icao', $flight['to']);

        $fromTime = Carbon::createFromFormat('H:i', $flight['fromTime']);
        $toTime   = Carbon::createFromFormat('H:i', $flight['toTime']);

        if ($toTime->lessThan($fromTime)) {
            $toTime->addDay();
        }

        $diff = $toTime->diff($fromTime);

        $flight['duration']     = sprintf('%dh %02dm', $diff->h, $diff->i);
        $flight['airline']      = $matchAirline['name'] ?? 'Unknown Airline';
        $flight['airline_logo'] = $matchAirline['logo'] ?? null;

        $flight['from']              = $matchAirFrom['name'] ?? null;
        $flight['FromCity']          = $matchAirFrom['city'] ?? null;
        $flight['airport_code_from'] = $matchAirFrom['iata'] ?? null;

        $flight['to']              = $matchAirTo['name'] ?? null;
        $flight['ToCity']          = $matchAirTo['city'] ?? null;
        $flight['airport_code_to'] = $matchAirTo['iata'] ?? null;

        return $flight;
    }
}
