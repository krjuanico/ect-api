<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangays', function (Blueprint $table) {
            $table->char('psgc_code', 10)->primary();
            $table->char('municipality_code', 10);
            $table->string('name');
            $table->timestamps();

            $table->foreign('municipality_code')->references('psgc_code')->on('municipalities')->restrictOnDelete();
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangays');
    }
};