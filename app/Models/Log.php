<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    protected $fillable = [
        'date',
        'employee_id',
        'subject',
        'description',
        'qty',
        'category_id',
        'task_id',
        'builder_id',
        'dweling_id',
        'status_id',
        'duration',
        'note',
        'work_time',
        'temp',
        'approved',
        'approved_date',
        'approved_note',
        'approved_emoji'
    ];

    protected $casts = [
        'date' => 'date',
        'qty' => 'integer',
        'duration' => 'decimal:2',
        'work_time' => 'datetime',
        'temp' => 'boolean',
        'approved' => 'boolean',
        'approved_date' => 'datetime'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function builder(): BelongsTo
    {
        return $this->belongsTo(Builder::class);
    }

    public function dweling(): BelongsTo
    {
        return $this->belongsTo(Dweling::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
