<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Reservation extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'guests',
        'date',
        'time',
        'special_requests',
    ];
}
