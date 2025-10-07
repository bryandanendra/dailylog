<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    protected $table = 'offwork'; // Use offwork table instead of leaves
    
    protected $fillable = [
        'title',
        'date',
        'leave_type',
        'employee_id',
        'description',
        'status',
        'archive'
    ];

    protected $casts = [
        'date' => 'date',
        'archive' => 'boolean'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
