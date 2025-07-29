<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Reserve;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReserveController extends Controller
{
    // create reservation
    public function getInformation(Request $request)
    {
        $formattedBirthday = $this->birthFunc($request);
        if (! empty($request->cart_id)) {
            $uuid      = Cart::where('id', intval($request->cart_id))->value('uuid');
            $flightIds = Cart::where('uuid', $uuid)->pluck('flight_id');
            $flightIds = $flightIds->toArray();
            logger($flightIds);

            if (empty($flightIds)) {
                $flightIds = [intval($request->flight_id)];
            }
        }
        $flightIds = $request->flight_id;
        foreach ($flightIds as $index => $flight_id) {
            logger('word');
            $reserve = Reserve::create([
                'user_id'              => intval($request->user_id),
                'flight_id'            => intval($flight_id),
                'cart_id'              => $request->cart_id ?? null,
                'uuid'                 => $uuid ?? null,
                'firstName'            => $request->firstName,
                'lastName'             => $request->lastName,
                'email'                => $request->email,
                'country'              => is_array($request->country) ? $request->country['value'] ?? null : $request->country,
                'country_code'         => is_array($request->country_code) ? $request->country_code['shortLabel'] ?? null : $request->country_code,
                'phone_no'             => $request->phone_no,
                'passenger_first_name' => $request->passenger_first_name,
                'passenger_last_name'  => $request->passenger_last_name,
                'gender'               => $request->gender,
                'birthday'             => $formattedBirthday,
                'nationality'          => is_array($request->nationality) ? $request->nationality['value'] ?? null : $request->nationality,
                'class'                => $request->class,
                'classPrice'           => $request->classPrice,
                'kg'                   => is_array($request->kg) ? $request->kg[$index] ?? null : $request->kg,
                'kgPrice'              => is_array($request->kgPrice) ? $request->kgPrice[$index] ?? null : $request->kgPrice,
                'seat'                 => is_array($request->seat) ? $request->seat[$index] ?? null : $request->seat,
                'seatPrice'            => is_array($request->seatPrice) ? $request->seatPrice[$index] ?? null : $request->seatPrice,
                'insurance'            => $request->insurance,
                'insurancePrice'       => $request->insurancePrice,
                'currency'             => $request->currency,
                'total'                => $request->total,
                'save'                 => $request->save,
            ]);
            $reserves[] = $reserve->toArray();
            logger($reserves);

        }

        if (count($reserves) === 1) {
            // Return single record without wrapping in array
            $reserveData = $reserves[0];
        } else {
            // Return array of multiple records
            $reserveData = $reserves;
        }
        return response()->json($reserveData, 200);

    }

    // delete reservation
    public function deleteReservation(Request $request)
    {
        Reserve::where('id', $request->reserveId)
            ->where('paymentStatus', 'pending')->delete();
        return response()->json(['message' => 'Unpaid'], 200);
    }

    // get Passenger
    public function getPassenger($id)
    {
        $passenger = Reserve::where('user_id', $id)
            ->where('save', true)
            ->select('id', 'save', 'passenger_first_name', 'passenger_last_name', 'birthday', 'gender', 'nationality')
            ->get()
            ->unique(function ($item) {
                return $item->passenger_first_name . '|' .
                $item->passenger_last_name . '|' .
                $item->birthday . '|' .
                $item->gender . '|' .
                $item->nationality;
            })
            ->values();
        return response()->json($passenger, 200);
    }

    // update
    public function updatePassenger(Request $request)
    {
        $formattedBirthday = $this->birthFunc($request);
        $reserves          = [];
        $reserveId         = $request->id;

        if (! is_array($request->id)) {
            $reserveId = [$request->reserveIds];
        }

        foreach ($reserveId as $reserve_id) {
            $reserve = Reserve::find($reserve_id);
            if ($reserve) {
                $reserve->firstName            = $request->firstName;
                $reserve->lastName             = $request->lastName;
                $reserve->email                = $request->email;
                $reserve->country              = is_array($request->country) ? $request->country['value'] : $request->country;
                $reserve->country_code         = is_array($request->country_code) ? $request->country_code['shortLabel'] : $request->country_code;
                $reserve->phone_no             = $request->phone_no;
                $reserve->passenger_first_name = $request->passenger_first_name;
                $reserve->passenger_last_name  = $request->passenger_last_name;
                $reserve->gender               = $request->gender;
                $reserve->birthday             = $formattedBirthday;
                $reserve->nationality          = is_array($request->nationality) ? $request->nationality['value'] : $request->nationality;

                $reserve->save();

                $reserves[] = $reserve->toArray();
            }
        }

        if (count($reserves) === 1) {
            $reserveData = $reserves[0]; // Single record, no array wrap
        } else {
            $reserveData = $reserves; // Multiple records, array of records
        }

        logger($reserveData);
        return response()->json($reserveData, 200);
    }

    // live search for passengers
    public function liveSearchPassenger(Request $request)
    {
        $search     = strtolower($request->get('query', ''));
        $pagination = Reserve::whereRaw('LOWER(passenger_first_name) LIKE ?', ["%{$search}%"])
            ->orWhereRaw('LOWER(passenger_last_name) LIKE ?', ["%{$search}%"])
            ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"])
            ->orWhereRaw('LOWER(bookStatus) LIKE ?', ["%{$search}%"])
            ->orWhereRaw('LOWER(class) LIKE ?', ["%{$search}%"])
            ->orWhereRaw('LOWER(seat) LIKE ?', ["%{$search}%"])
            ->orWhereRaw('LOWER(paymentStatus) LIKE ?', ["%{$search}%"])
            ->paginate(10);

        return response()->json($pagination);

    }

    private function birthFunc($request)
    {
        $birthday = is_array($request->birthday) ? $request->birthday : json_decode($request->birthday, true);

        // Compose date string as "YYYY-MM-DD" directly, since month is numeric
        $year  = $birthday['year'];
        $month = str_pad($birthday['month'], 2, '0', STR_PAD_LEFT); // ensure 2 digits
        $day   = str_pad($birthday['day'], 2, '0', STR_PAD_LEFT);

        $dateString = "$year-$month-$day";

        return Carbon::parse($dateString)->format('Y-m-d');
    }
}
