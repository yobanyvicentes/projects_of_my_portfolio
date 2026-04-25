<?php

namespace App\Models;

use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_id',
        'uploaded_by',
        'title',
        'amount',
        'file_path',
        'file_type',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::created(function (Receipt $receipt): void {
            ActivityLogger::log($receipt->flat_id, 'receipt.uploaded', $receipt->title);
        });

        static::deleted(function (Receipt $receipt): void {
            ActivityLogger::log($receipt->flat_id, 'receipt.deleted', $receipt->title);
        });
    }

    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
