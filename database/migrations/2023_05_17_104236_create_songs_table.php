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
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('artist');
            $table->binary('song_file');
            $table->unsignedBigInteger('album_id')->nullable();
            $table->unsignedBigInteger('singer_id');
            $table->timestamps();
            $table->foreign('album_id')->references('id')->on('albums');
            $table->foreign('singer_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
