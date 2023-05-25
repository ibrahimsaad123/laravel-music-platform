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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->enum('person_type', ['user', 'singer']);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('verification_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

/*
 *
 *
 *
 *
 *  public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('singer_id');
            $table->date('release_date')->nullable();
            $table->timestamps();
            /*
             * Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('artist');
            $table->binary('song_file');
            $table->unsignedBigInteger('album_id');
            $table->timestamps();

            $table->foreign('album_id')->references('id')->on('albums');
        });

});
}
 */

/*
 *
 *
 *
 *  public function up(): void
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
    } */
