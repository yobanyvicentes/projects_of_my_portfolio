<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'membership_id',
    'phone',
    'emergency_contact_name',
    'emergency_contact_phone',
    'bank_account_name',
    'bank_account_number',
    'notes',
])]
class MemberProfile extends Model
{
    use HasFactory;

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }
}
