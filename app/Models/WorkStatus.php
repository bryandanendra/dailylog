<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkStatus extends Model
{
    protected $fillable = [
        'title',
        'description',
        'archive'
    ];

    protected $casts = [
        'archive' => 'boolean'
    ];
}
