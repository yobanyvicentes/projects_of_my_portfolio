<?php

namespace App\Models;

use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'flat_id',
    'assigned_to',
    'title',
    'description',
    'status',
    'due_date',
])]
class Chore extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Chore $chore): void {
            ActivityLogger::log($chore->flat_id, 'chore.created', $chore->title);
        });

        static::updated(function (Chore $chore): void {
            if ($chore->wasChanged('status') && $chore->status === 'done') {
                ActivityLogger::log($chore->flat_id, 'chore.completed', $chore->title);
            }
        });
    }

    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
