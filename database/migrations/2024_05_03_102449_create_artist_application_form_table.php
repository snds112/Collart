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
        Schema::create('artist_application_form', function (Blueprint $table) {
            $table->id()->primary()->autoIncrement()->unique();
            $table->string("fullname", 100)->unique();
            $table->string("phone", 32)->unique();
            $table->string("portfolio", 255);
            $table->string("art_description", 511);
            $table->unsignedBigInteger('account_id');
            $table->enum("status", ["approved", "pending", "rejected"])->default("pending");
            $table->foreign('account_id')->references('id')->on('account')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist_application_form');
    }
};
