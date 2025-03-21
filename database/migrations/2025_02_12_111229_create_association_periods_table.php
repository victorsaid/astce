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
        Schema::create('association_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('associate_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Pode ser nulo para associações ainda ativas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('association_periods');
    }
};
