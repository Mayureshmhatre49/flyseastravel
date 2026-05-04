<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'package_id', 'name', 'phone', 'email',
        'destination', 'travel_dates', 'message', 'status', 'source',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
