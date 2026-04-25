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
        Schema::create('scenarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->decimal('company_a_price', 10, 2);
            $table->decimal('company_b_price', 10, 2);
            $table->decimal('company_a_ad_budget', 12, 2)->default(0);
            $table->decimal('company_b_ad_budget', 12, 2)->default(0);
            $table->unsignedInteger('consumers_count')->default(500);
            $table->unsignedInteger('periods_count')->default(10);
            $table->string('market_type')
                ->default('perfect_competition');
            $table->string('competitive_strategy')->nullable();
            $table->boolean('is_example')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenarios');
    }
};
