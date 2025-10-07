<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeCutoff extends Model
{
    protected $table = 'time_cutoff';
    
    protected $fillable = [
        'time',
        'day_offset',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'day_offset' => 'integer'
    ];
}
