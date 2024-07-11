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
        Schema::create('clients_services_sections_private_datas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')
                ->constrained('services')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('ip');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('info');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients_services_sections_private_datas');
    }
};
