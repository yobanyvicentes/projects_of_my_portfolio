<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colfuturo_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('promotion_year')->nullable()->index();
            $table->string('name');
            $table->string('gender')->nullable()->index();
            $table->string('department')->nullable()->index();
            $table->string('undergraduate_university')->nullable()->index();
            $table->string('undergraduate_program')->nullable()->index();
            $table->string('postgraduate_university')->nullable()->index();
            $table->string('country')->nullable()->index();
            $table->string('destination_city')->nullable()->index();
            $table->string('postgraduate_type')->nullable()->index();
            $table->string('postgraduate_program')->nullable()->index();
            $table->string('area')->nullable()->index();
            $table->string('status')->nullable()->index();
            $table->text('search_vector')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colfuturo_profiles');
    }
};
