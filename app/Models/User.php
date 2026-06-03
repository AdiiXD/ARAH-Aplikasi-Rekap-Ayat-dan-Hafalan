<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role', 'profile_picture'];
    public $timestamps = true;

    // Relasi ke santri sebagai ustadz
    public function santrisAsUstadz()
    {
        if ($this->role === 'ustadz') {
            return $this->hasMany(Santri::class, 'ustadz_id');
        }
        return null;
    }

    // Relasi ke santri sebagai orang tua (many-to-many)
    public function santrisAsOrangTua()
    {
        return $this->belongsToMany(Santri::class, 'orangtua_santri', 'orangtua_id', 'santri_id');
    }

    // Relasi ke bookmark
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    // Relasi ke listening logs
    public function listeningLogs()
    {
        return $this->hasMany(QuranListeningLog::class);
    }

    // Relasi ke notifikasi
    public function notifications()
    {
        return $this->hasMany(Notifikasi::class);
    }

    // Relasi ke settings (user preferences)
    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function getProfilePictureUrl()
    {
        if ($this->profile_picture && file_exists(__DIR__ . '/../../public/uploads/profile/' . $this->profile_picture)) {
            // Mengembalikan path relatif dari folder public
            return 'uploads/profile/' . $this->profile_picture;
        }
        // Default avatar dari ui-avatars.com
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?background=4A1D2E&color=fff&name={$name}";
    }
}
