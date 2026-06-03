<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'bookmarks';
    protected $fillable = ['user_id', 'surah', 'ayat', 'surah_name'];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}