<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->char('psgc_code', 10)->primary();
            $table->char('region_code', 10);
            $table->char('province_code', 10)->nullable(); // null for NCR and independent cities
            $table->string('name');
            $table->string('type', 10)->nullable();        // City, Mun, SubMun
            $table->timestamps();

            $table->foreign('region_code')->references('psgc_code')->on('regions')->restrictOnDelete();
            $table->foreign('province_code')->references('psgc_code')->on('provinces')->restrictOnDelete();
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
};