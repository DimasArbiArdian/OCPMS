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
        Schema::create('candidate_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->enum('dp_status', ['done', 'pending'])->default('pending');
            $table->enum('medical_status', ['done', 'not_yet'])->default('not_yet');
            $table->enum('visa_status', ['process', 'approved', 'rejected'])->default('process');
            $table->enum('ticket_status', ['booked', 'not_yet'])->default('not_yet');
            $table->date('departure_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_progress');
    }
};
