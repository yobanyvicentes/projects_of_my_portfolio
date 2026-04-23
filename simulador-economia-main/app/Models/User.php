<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_guest',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_guest' => 'boolean',
    ];

    public function scenarios(): HasMany
    {
        return $this->hasMany(Scenario::class);
    }

    public function simulationRuns(): HasMany
    {
        return $this->hasMany(SimulationRun::class);
    }

    public function isGuest(): bool
    {
        return (bool) $this->is_guest;
    }
}
