<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavoriteSinger extends Model
{
    use HasFactory;


    protected $table = 'user_favorite_singers';

    protected $fillable = [
        'user_id',
        'singer_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function singer()
    {
        return $this->belongsTo(User::class, 'singer_id');
    }

}
