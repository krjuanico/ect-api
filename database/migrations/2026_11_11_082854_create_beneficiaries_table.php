<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();

            // Text, not a number: keeps all 16 digits. Nullable because not every row has one,
            // and MySQL allows many NULLs in a unique column.
            $table->char('pcn', 16)->nullable()->unique();

            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name', 20)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('sex', 6)->nullable();

            // Latest known address. City/municipality, province and region come from the barangay.
            $table->char('barangay_code', 10)->nullable();
            $table->string('purok', 100)->nullable();

            $table->timestamps();

            $table->foreign('barangay_code')->references('psgc_code')->on('lib_barangays')->restrictOnDelete();
            $table->index(['last_name', 'first_name', 'birthdate']);
            $table->index('barangay_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};