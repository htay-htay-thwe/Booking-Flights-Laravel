<?php
namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Reserve;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeatController extends Controller
{
    // Seat layout generation: 31 rows, 6 seats (A-F)
    private function generateSeats()
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

    // Show seat map for user/admin
    public function showSeats($flightId)
    {

        $reserves = DB::table('reserves')
            ->join('flights', 'reserves.flight_id', '=', 'flights.id')
            ->select('reserves.passenger_first_name', 'reserves.passenger_last_name', 'reserves.seat', 'flights.id')
            ->where('reserves.flight_id', $flightId)
            ->get();

        $flight     = Flight::where('id', $flightId)->first()->toArray();
        $flightData = $this->encodeJson($flight);
        $allSeats   = $this->generateSeats();

        $assignedSeat = DB::table('reserves')
            ->join('flights', 'reserves.flight_id', '=', 'flights.id')
            ->select('reserves.passenger_first_name', 'reserves.user_id', 'reserves.passenger_last_name', 'reserves.seat', 'reserves.birthday', 'reserves.gender', 'flights.id')
            ->where('reserves.flight_id', $flightId)
            ->whereNull('reserves.seat')
            ->get();

        $seatUserMap = [];
        foreach ($reserves as $reserve) {
            $seatUserMap[$reserve->seat] = trim("{$reserve->passenger_first_name} {$reserve->passenger_last_name}") ?: 'Unknown';
        }

        // Gather booked seats
        $bookedSeats = $reserves->pluck('seat')->toArray();
        return view('seats.map', compact('reserves', 'allSeats', 'bookedSeats', 'flightData', 'seatUserMap', 'assignedSeat'));
    }

    // Book seat (user)
    public function bookSeat(Request $request)
    {
        // $request->validate([
        //     'seat_number' => 'required|string',
        // ]);

        // Check seat availability
        $record = Reserve::where('flight_id', (int) $request->flight_id)
            ->where('user_id', (int) $request->selected_user)
            ->where('passenger_first_name', $request->first_name)
            ->where('passenger_last_name', $request->last_name)
            ->where('bookStatus', 'confirmed')
            ->whereNull('seat')
            ->first();

        if ($record) {
            $record->update([
                'seat' => $request->selected_seat,
            ]);
        }

        return back()->with('success', "Seat {$request->selected_seat} assigned to {$request->first_name} {$request->last_name} successfully!");
    }

    // Admin: release/cancel seat
    public function adminCancelSeat(Request $request, $reservationId)
    {
        $reservation         = Reserve::findOrFail($reservationId);
        $reservation->status = 'canceled';
        $reservation->save();

        return back()->with('success', 'Reservation canceled');
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
}
