<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clean_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->nullable()->constrained('import_batches')->nullOnDelete();
            $table->foreignId('disaster_event_id')->constrained('disaster_events')->restrictOnDelete();
            $table->foreignId('beneficiary_id')->constrained('beneficiaries')->restrictOnDelete();

            $table->string('control_number', 50);

            // Text, not a number: keeps the leading zero
            $table->char('gcash', 11)->nullable();

            // Region, province and city/municipality come from this barangay through the PSGC tables
            $table->char('barangay_code', 10);
            $table->decimal('amount', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('barangay_code')->references('psgc_code')->on('lib_barangays')->restrictOnDelete();

            // A person appears once per disaster event, and control numbers are unique within an event
            $table->unique(['disaster_event_id', 'beneficiary_id']);
            $table->unique(['disaster_event_id', 'control_number']);
            $table->index('gcash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clean_list');
    }
};