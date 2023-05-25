<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'release_date',
    ];
    protected $table = 'albums';

    public function songs()
    {
        return $this->hasMany(Songs::class);
    }
    /*public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }*/
    public function image()
    {
        return $this->hasOne(Image::class, 'imageable_id')->where('imageable_type', 'App\ِAlbum');
    }

    public function singer()
    {
        return $this->belongsTo(User::class, 'singer_id');
    }


}
