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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('date');
            //$table->json('participants')->nullable();
            $table->json('attachments')->nullable();
            $table->json('photos')->nullable();


            $table->timestamps();
        });

        Schema::create('meeting_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
        Schema::dropIfExists('meeting_user');
    }
};
