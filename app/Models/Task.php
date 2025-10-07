<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'archive'
    ];

    protected $casts = [
        'archive' => 'boolean'
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }
}
