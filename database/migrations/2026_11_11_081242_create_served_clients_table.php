<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('served_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->nullable()->constrained('import_batches')->nullOnDelete();
            $table->foreignId('beneficiary_id')->constrained('beneficiaries')->restrictOnDelete();

            // Were text columns (program, sdo); nullable because old rows may leave them blank
            $table->foreignId('program_id')->nullable()->constrained('programs')->restrictOnDelete();
            $table->foreignId('sdo_id')->nullable()->constrained('special_disbursing_officers')->restrictOnDelete();
            $table->foreignId('disaster_event_id')->nullable()->constrained('disaster_events')->restrictOnDelete();

            $table->string('control_number', 50)->nullable();

            // Text, not a number: keeps the leading zero
            $table->char('gcash', 11)->nullable();

            // Replaces province, city_municipality and barangay text through PSGC
            $table->char('barangay_code', 10)->nullable();
            $table->string('purok', 100)->nullable();

            $table->date('date_served')->nullable();      // was date_last_served
            $table->string('served_location')->nullable(); // was last_served_location
            $table->string('partners')->nullable();
            $table->string('in_charge')->nullable();       // was incharge
            $table->text('other')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();                          // created_at replaces date_imported

            $table->foreign('barangay_code')->references('psgc_code')->on('lib_barangays')->restrictOnDelete();

            $table->index(['beneficiary_id', 'program_id']);
            $table->index('control_number');
            $table->index('gcash');
            $table->index('date_served');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('served_clients');
    }
};