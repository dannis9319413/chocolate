<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id',
        'distributor_id',
        'bank_account',
        'email',
        'name',
        'phone',
        'address',
        'total',
        'shipment',
    ];
}
