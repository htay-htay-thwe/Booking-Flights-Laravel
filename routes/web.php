<?php

use App\Http\Controllers\Api\ReserveController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/forgot-password', [UserController::class, 'forgotPw'])->middleware('guest')->name('password.request');
Route::get('/reset-password/{token}', [UserController::class, 'resetPw'])->middleware('guest')->name('password.reset');

// Route::get('/google/redirect', [UserController::class, 'googleRedirect'])->name('google#redirect');
// Route::get('/google/callback', [UserController::class, 'googleCallback'])->name('google#callback');

// Route::get('/facebook/redirect', [UserController::class, 'facebookRedirect'])->name('facebook#redirect');
// Route::get('/facebook/callback', [UserController::class, 'facebookCallback'])->name('facebook#callback');

// Route::get('/github/redirect', [UserController::class, 'githubRedirect'])->name('github#redirect');
// Route::get('/github/callback', [UserController::class, 'githubCallback'])->name('github#callback');

Route::prefix('auth')->group(function () {

    // register
    Route::post('/register', [UserController::class, 'registerPost'])->name('register#action');
    // login
    Route::post('/login', [UserController::class, 'loginPost'])->name('login#action');
    // logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/forgot/pw', [UserController::class, 'forgotPasswordPage'])->name('forgot#pw');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard#page');
    Route::get('/admin/delete/{id}', [UserController::class, 'adminDelete'])->name('admin#delete');
    Route::get('/settings', [UserController::class, 'settingPage'])->name('settings');
    Route::post('/settings/password', [UserController::class, 'updatePassword'])->name('settings#password');
    Route::post('/settings/email', [UserController::class, 'updateEmail'])->name('settings#email');
    Route::post('/settings/profile', [UserController::class, 'updateProfile'])->name('settings#profile');

    Route::prefix('flight')->group(function () {
        Route::get('/create/page', [FlightController::class, 'createFlightPage'])->name('create#flight#page');
        Route::post('/create', [FlightController::class, 'createFlight'])->name('create#flight');

        Route::get('/lists', [FlightController::class, 'listPage'])->name('list#page');
        // Route::get('/get/lists', [FlightController::class, 'getFlights'])->name('flights#get');

        Route::get('/delete/{id}', [FlightController::class, 'deleteFlights'])->name('flights#delete');
        Route::get('/edit/{id}', [FlightController::class, 'editFlights'])->name('flights#edit');
        Route::post('/update', [FlightController::class, 'updateFlights'])->name('flights#update');

        Route::get('/sort', [FlightController::class, 'ajaxSort'])->name('flight#sort');
        Route::get('/search', [FlightController::class, 'liveSearch'])->name('flight#search');

        Route::get('/details/page/{id}', [FlightController::class, 'detailPage'])->name('flight#detail');
        Route::get('/update/book/status/{id}', [FlightController::class, 'updateBook'])->name('email#sent');
        Route::get('/update/checkIn/status/{id}', [FlightController::class, 'updateCheckIn'])->name('update#checkIn');
        Route::get('/cancel/booking/status/{id}', [FlightController::class, 'cancelBooking'])->name('cancel#booking');

        Route::get('/{flight}/seats', [SeatController::class, 'showSeats'])->name('seats#show');
        Route::post('/assign-seat', [SeatController::class, 'bookSeat'])->name('assign#seat');
        Route::post('/admin/reservations/{reservation}/cancel', [SeatController::class, 'adminCancelSeat'])->name('admin#reservations#cancel');

        Route::get('/passenger/search', [ReserveController::class, 'liveSearchPassenger'])->name('flight#searchPassenger');

    });

});
