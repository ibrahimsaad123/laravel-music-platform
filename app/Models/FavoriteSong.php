<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteSong extends Model
{
    use HasFactory;
    protected $table = 'favorite_songs';

    // تعريف العلاقات اللازمة
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function song()
    {
        return $this->belongsTo(Songs::class, 'song_id');
    }
}
