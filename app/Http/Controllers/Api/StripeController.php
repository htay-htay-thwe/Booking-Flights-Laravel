<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Reserve;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $amount = intval($request->amount * 100); // in USD
                                                  // $amountInCents = $amount * 100;
        try {
            $paymentIntent = PaymentIntent::create([
                'amount'                    => $amount,
                'currency'                  => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            logger('Stripe PaymentIntent create error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment intent creation failed'], 500);
        }

    }

    public function updatePaymentStatus(Request $request)
    {
        logger($request->all());
        if (! empty($request->uuid)) {
            Reserve::where('uuid', $request->uuid)->update([
                'paymentStatus' => $request->paymentStatus,
            ]);
            Cart::where('uuid', $request->uuid)->delete();

        } else {
            $reserveId = $request->reserveId;
            if (! is_array($request->reserveId)) {
                $reserveId = [$request->reserveIds];
            }
            foreach ($reserveId as $reserve_id) {
                Reserve::where('id', $reserve_id)->update([
                    'paymentStatus' => $request->paymentStatus,
                ]);
            }
        }
        return response()->json(['message' => 'Payment status updated']);
    }
}
