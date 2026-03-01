<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingVisit extends Model
{
    protected $fillable = [
        'fingerprint',
        'visited_on',
        'is_unique',
    ];

    protected $casts = [
        'visited_on' => 'date',
        'is_unique' => 'boolean',
    ];
}
