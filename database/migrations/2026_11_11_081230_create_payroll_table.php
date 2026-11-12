<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_no', 30)->unique();
            $table->foreignId('disaster_event_id')->constrained('disaster_events')->restrictOnDelete();
            $table->foreignId('tranche_id')->constrained('tranche')->restrictOnDelete();
            $table->foreignId('sdo_id')->nullable()->constrained('special_disbursing_officers')->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['disaster_event_id', 'tranche_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll');
    }
};