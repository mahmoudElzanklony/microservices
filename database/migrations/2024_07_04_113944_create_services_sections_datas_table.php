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
        Schema::create('services_sections_datas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')
                ->constrained('services')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('section_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('type')->default('public');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_sections_datas');
    }
};
