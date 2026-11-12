<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disaster_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->restrictOnDelete();
            $table->foreignId('fund_source_id')->nullable()->constrained('fund_sources')->restrictOnDelete();
            $table->string('name');
            $table->string('disaster_type', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['program_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disaster_events');
    }
};