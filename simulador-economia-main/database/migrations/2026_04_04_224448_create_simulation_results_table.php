<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('simulation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('simulation_run_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedInteger('period')->default(1);
            $table->unsignedInteger('company_a_sales')->default(0);
            $table->unsignedInteger('company_b_sales')->default(0);
            $table->decimal('company_a_market_share', 8, 4)->default(0);
            $table->decimal('company_b_market_share', 8, 4)->default(0);
            $table->decimal('company_a_profit', 14, 2)->default(0);
            $table->decimal('company_b_profit', 14, 2)->default(0);
            $table->decimal('hhi', 8, 4)->default(0);
            $table->string('leader_company')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulation_results');
    }
};
