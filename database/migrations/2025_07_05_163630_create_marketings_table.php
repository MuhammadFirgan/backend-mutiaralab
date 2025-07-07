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
        Schema::create('marketings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            // $table->foreignId('koor_teknis_id');
            $table->date("tgl_kajian");
            $table->enum("status", ["accept marketing", "decline marketing"]);
            $table->string("ket_kajian");
            $table->string("document_path");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketings');
    }
};
