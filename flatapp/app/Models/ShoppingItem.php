<?php

namespace App\Models;

use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'flat_id',
    'added_by',
    'name',
    'quantity',
    'status',
])]
class ShoppingItem extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function (ShoppingItem $item): void {
            ActivityLogger::log($item->flat_id, 'shopping.added', $item->name);
        });

        static::updated(function (ShoppingItem $item): void {
            if ($item->wasChanged('status') && $item->status === 'bought') {
                ActivityLogger::log($item->flat_id, 'shopping.bought', $item->name);
            }
        });
    }

    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
