<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SimulationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'simulation_run_id',
        'period',
        'company_a_sales',
        'company_b_sales',
        'company_a_market_share',
        'company_b_market_share',
        'company_a_profit',
        'company_b_profit',
        'hhi',
        'leader_company',
        'raw_data',
    ];

    protected $casts = [
        'company_a_market_share' => 'decimal:4',
        'company_b_market_share' => 'decimal:4',
        'company_a_profit' => 'decimal:2',
        'company_b_profit' => 'decimal:2',
        'hhi' => 'decimal:4',
        'raw_data' => 'array',
    ];

    public function run(): BelongsTo
    {
        return $this->belongsTo(SimulationRun::class, 'simulation_run_id');
    }

    public function simulationRun(): BelongsTo
    {
        return $this->run();
    }
}
