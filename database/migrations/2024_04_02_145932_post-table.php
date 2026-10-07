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
        Schema::create("post", function (Blueprint $table) {
            $table->id()->primary()->autoIncrement();
            $table->string("caption", 200)->default("");
            $table->integer("like_count")->default(0);
            $table->enum('type', ['image', 'audio', 'video']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("post");
    }
};
