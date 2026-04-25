<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionPlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_session_id',
        'user_id',
        'nickname',
        'score',
        'status',
        'joined_at',
        'last_seen_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function gameSession(): BelongsTo
    {
        return $this->belongsTo(GameSession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(PlayerAnswer::class);
    }
}
