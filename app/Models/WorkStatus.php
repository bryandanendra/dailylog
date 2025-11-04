<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkStatus extends Model
{
    protected $table = 'work_status'; // Specify table name explicitly
    
    protected $fillable = [
        'title',
        'description',
        'archive'
    ];

    protected $casts = [
        'archive' => 'boolean'
    ];
}
