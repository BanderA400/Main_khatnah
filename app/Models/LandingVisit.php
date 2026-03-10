<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingVisit extends Model
{
    protected $attributes = [
        'is_unique' => true,
        'visits_count' => 1,
    ];

    protected $fillable = [
        'fingerprint',
        'visited_on',
        'is_unique',
        'visits_count',
    ];

    protected $casts = [
        'visited_on' => 'date',
        'is_unique' => 'boolean',
        'visits_count' => 'integer',
    ];
}
