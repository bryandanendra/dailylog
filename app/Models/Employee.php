<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'username',
        'email',
        'email_verified_at',
        'password',
        'join_date',
        'is_admin',
        'can_approve',
        'cutoff_exception',
        'is_supervisor',
        'division_id',
        'sub_division_id',
        'role_id',
        'position_id',
        'description',
        'archive',
        'is_approved',
        'user_id',
        'superior_id'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'join_date' => 'date',
        'is_admin' => 'boolean',
        'can_approve' => 'boolean',
        'cutoff_exception' => 'boolean',
        'is_supervisor' => 'boolean',
        'archive' => 'boolean',
        'is_approved' => 'boolean'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function subDivision(): BelongsTo
    {
        return $this->belongsTo(SubDivision::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    public function offwork(): HasMany
    {
        return $this->hasMany(Offwork::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function superior(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'superior_id');
    }
}
