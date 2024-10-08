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
        Schema::create('services_privileges_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_privilege_id')->constrained('services_privileges')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('privilege_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_privileges_controls');
    }
};
