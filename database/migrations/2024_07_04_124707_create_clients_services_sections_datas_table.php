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
        Schema::create('clients_services_sections_datas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_section_data_id');

            $table->foreign('service_section_data_id','fk_s_d')->references('id')
                ->on('clients_services_sections_private_datas')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->text('answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients_services_sections_datas');
    }
};
