<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'company_a_price',
        'company_b_price',
        'company_a_ad_budget',
        'company_b_ad_budget',
        'consumers_count',
        'periods_count',
        'market_type',
        'competitive_strategy',
        'is_example',
    ];

    protected $casts = [
        'company_a_price' => 'decimal:2',
        'company_b_price' => 'decimal:2',
        'company_a_ad_budget' => 'decimal:2',
        'company_b_ad_budget' => 'decimal:2',
        'consumers_count' => 'integer',
        'periods_count' => 'integer',
        'is_example' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function runs(): HasMany
    {
        return $this->hasMany(SimulationRun::class);
    }

    public function simulationRuns(): HasMany
    {
        return $this->runs();
    }
}
