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
        Schema::create("account", function (Blueprint $table) {
            $table->id()->primary()->autoIncrement()->unique();
            $table->string("username", 30)->unique();
            $table->string("email", 255)->unique();
            $table->string("password", 511);
            $table->string("avatar", 255)->default('/storage/avatars/default.png ');
            $table->timestamps();
            $table->enum("type", ["artist", "visitor", "admin"])->default("visitor");
            $table->boolean("artist_status")->default(false);
            $table->string("bio", 500)->nullable();
            $table->string('remember_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("account");
    }
};
