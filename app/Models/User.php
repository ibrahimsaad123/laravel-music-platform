<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens;

    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password','person_type' ,'verification_token'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailNotification($this->verification_token));
    }*/


    public function songs()
    {
        return $this->hasMany(Songs::class, 'singer_id');
    }


    public function albums()
    {
        return $this->hasMany(Album::class, 'singer_id');
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'imageable_id')->where('imageable_type', 'App\User');
    }

    public function favoriteSongs()
    {
        return $this->hasMany(FavoriteSong::class, 'user_id');
    }


/*
    public function favoriteSingers()
    {
        return $this->hasMany(UserFavoriteSinger::class, 'user_id');
    }

    public function fans()
    {
        return $this->hasMany(UserFavoriteSinger::class, 'singer_id');
    }*/
    public function favoriteSingers()
    {
        return $this->belongsToMany(User::class, 'user_favorite_singers', 'user_id', 'singer_id');
    }

    public function fans()
    {
        return $this->belongsToMany(User::class, 'user_favorite_singers', 'singer_id', 'user_id');
    }
}
