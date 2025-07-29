<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DateController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\ReserveController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::get('/google/redirect', [AuthController::class, 'googleRedirect'])->name('google#redirect');
Route::post('/google/callback', [AuthController::class, 'googleCallback'])->name('google#callback');

Route::get('/facebook/redirect', [AuthController::class, 'facebookRedirect'])->name('facebook#redirect');
Route::post('/facebook/callback', [AuthController::class, 'facebookCallback'])->name('facebook#callback');

Route::get('/github/redirect', [AuthController::class, 'githubRedirect'])->name('github#redirect');
Route::post('/github/callback', [AuthController::class, 'githubCallback'])->name('github#callback');
// Other public API routes
Route::middleware('auth:api')->get('/user-info', [AuthController::class, 'userInfo']);

// Important: login/logout routes must use 'web' middleware for sessions!
// Protected routes with Sanctum auth
Route::middleware(['jwt.auth'])->group(function () {
    Route::post('/change/pw', [AuthController::class, 'changePassword'])->name('change#pw');
    Route::post('/change/email', [AuthController::class, 'changeEmail'])->name('change#email');
    Route::post('/change/profile', [AuthController::class, 'changeProfile'])->name('change#profile');
    Route::get('/airports', [FlightController::class, 'getAirports'])->name('get#airports');
    Route::post('/search/airports', [FlightController::class, 'searchAirports'])->name('search#airports');
    Route::get('/get/flight/info/{id}', [FlightController::class, 'flightInfo']);
    Route::post('/direct/get/flight/info', [FlightController::class, 'flightInfoDirect']);
    Route::get('/get/bookings/{id}', [FlightController::class, 'getBookings']);
    Route::get('/get/seats/{id}', [FlightController::class, 'getSeats']);
    Route::get('/get/seats/array/{outboundId}/{id}', [FlightController::class, 'getSeatsArray']);

    Route::get('/generate-dates', [DateController::class, 'generateYearDates']);

    Route::get('/add/cart/{flight_id}/{user_id}', [CartController::class, 'addCart']);
    Route::post('/add/cart/multiple', [CartController::class, 'addCartMultiple']);
    Route::get('/get/cart/{user_id}', [CartController::class, 'getCarts']);
    Route::get('remove/cart/{cart_id}/{user_id}', [CartController::class, 'removeCart']);

    Route::get('country_name', [CountryController::class, 'countryName']);

    Route::post('/get/information', [ReserveController::class, 'getInformation']);
    Route::post('/delete/unpaid-reserve', [ReserveController::class, 'deleteReservation']);
    Route::post('/update-reservation/passenger/info', [ReserveController::class, 'updatePassenger']);
    Route::get('/passenger/{id}', [ReserveController::class, 'getPassenger']);

    Route::post('/create-payment-intent', [StripeController::class, 'createPaymentIntent']);
    Route::post('/payment/update-status', [StripeController::class, 'updatePaymentStatus']);

});
