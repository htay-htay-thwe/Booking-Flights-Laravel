<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class CountryController extends Controller
{
    // get Country
    public function countryName()
    {
        $callingJson      = Storage::get('country-by-calling-code.json');
        $currencyJson     = Storage::get('country-by-currency-code.json');
        $abbreviationJson = Storage::get('country-by-abbreviation.json');

        $callingCodes  = collect(json_decode($callingJson, true));
        $currencyCodes = collect(json_decode($currencyJson, true));
        $abbreviation  = collect(json_decode($abbreviationJson, true));

// Merge by country name
        $merged = $callingCodes->map(function ($item) use ($currencyCodes, $abbreviation) {
            $currency = $currencyCodes->firstWhere('country', $item['country']);
            $abb      = $abbreviation->firstWhere('country', $item['country']);
            return [
                'country'       => $item['country'],
                'calling_code'  => $item['calling_code'],
                'currency_code' => $currency['currency_code'] ?? null,
                'abbreviation'  => $abb['abbreviation'] ?? null,
            ];
        });

        return response()->json($merged->values(), 200);

    }

}
