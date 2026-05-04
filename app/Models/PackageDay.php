<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageDay extends Model
{
    protected $fillable = [
        'package_id', 'day_number', 'title', 'location', 'description', 'activities',
    ];

    protected $casts = [
        'activities' => 'array',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
