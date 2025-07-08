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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('doc_name');
            $table->string("doc_date");
            $table->string("doc_number");
            $table->text("doc_desc");
            $table->string("image_path");
            $table->string("doc_year");
            $table->enum('status', ['pending', 'accept', 'decline'])->default("pending");
            // $table->foreignId("marketing_id")->constrained("marketing")->onDelete("cascade");
            // $table->foreignId("marketing_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
