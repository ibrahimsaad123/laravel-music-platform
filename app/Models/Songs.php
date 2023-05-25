<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Songs extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'song_file',
        'album_id',
        'singer_id',
    ];

    protected $table = 'songs';

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
    /*
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }*/
    public function image()
    {
        return $this->hasOne(Image::class, 'imageable_id')->where('imageable_type', 'App\Song');
    }

    public function singer()
    {
        return $this->belongsTo(User::class, 'singer_id');
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorite_songs', 'song_id', 'user_id');
    }

}
