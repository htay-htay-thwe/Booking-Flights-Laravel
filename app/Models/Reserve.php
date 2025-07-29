<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'flight_id',
        'cart_id',
        'uuid',
        'firstName',
        'lastName',
        'email',
        'country',
        'country_code',
        'phone_no',
        'passenger_first_name',
        'passenger_last_name',
        'gender',
        'birthday',
        'nationality',
        'class',
        'classPrice',
        'kg',
        'kgPrice',
        'seat',
        'seatPrice',
        'insurance',
        'insurancePrice',
        'currency',
        'total',
        'checkInStatus',
        'bookStatus',
        'paymentStatus',
        'save',
    ];
}
