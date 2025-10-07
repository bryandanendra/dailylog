<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    protected $fillable = [
        'title',
        'description',
        'archive'
    ];

    protected $casts = [
        'archive' => 'boolean'
    ];

    public function subDivisions(): HasMany
    {
        return $this->hasMany(SubDivision::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
