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
        Schema::create('clients_services_tickets_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_private_answer_id');

            $table->foreign('service_private_answer_id','fk_c_s_t_c')->references('id')
                ->on('clients_services_sections_private_datas')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('content');
            $table->string('type');
            $table->datetimes('seen_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients_services_tickets_chats');
    }
};
