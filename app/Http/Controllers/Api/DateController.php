<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DateController extends Controller
{
       // all years dates
    public function generateYearDates()
    {
        $year  = now()->year;
        $start = Carbon::create($year, 1, 1);
        $end   = Carbon::create($year, 12, 31);
        $dates = [];

        while ($start->lte($end)) {
            $dates[] = $start->toDateString();
            $start->addDay();
        }


        return response()->json($dates,200);
    }
}
