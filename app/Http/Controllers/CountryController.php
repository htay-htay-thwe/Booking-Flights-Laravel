<?php
namespace App\Http\Controllers;

class CountryController extends Controller
{
    // get Country
    public function countryName()
    {
        $callingJson      = base_path('resources/data/country-by-calling-code.json');
        $currencyJson     = base_path('resources/data/country-by-currency-code.json');
        $abbreviationJson = base_path('resources/data/country-by-abbreviation.json');

        $callingCodes  = json_decode(file_get_contents($callingJson));
        $currencyCodes = json_decode(file_get_contents($currencyJson));
        $abbreviation  = json_decode(file_get_contents($abbreviationJson));

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
