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
        Schema::create('koor_teknis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('marketings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date("tgl_masuk");
            $table->enum('status', ['accept koor teknis', 'decline koor teknis']);
            $table->string("document_path");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koor_teknis');
    }
};
