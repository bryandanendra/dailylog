<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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
        'archive'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'join_date' => 'date',
            'is_admin' => 'boolean',
            'can_approve' => 'boolean',
            'cutoff_exception' => 'boolean',
            'is_supervisor' => 'boolean',
            'archive' => 'boolean'
        ];
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function subDivision()
    {
        return $this->belongsTo(SubDivision::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'employee_id');
    }

    public function offwork()
    {
        return $this->hasMany(Offwork::class, 'employee_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'employee_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }
}
