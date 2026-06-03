<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role'];
    public $timestamps = true;

    public function santrisAsUstadz()
    {
        if ($this->role === 'ustadz') {
            return $this->hasMany(Santri::class, 'ustadz_id');
        }
        return null;
    }

    public function santrisAsOrangTua()
    {
        return $this->belongsToMany(Santri::class, 'orangtua_santri', 'orangtua_id', 'santri_id');
    }
    public function orangTua()
    {
        return $this->belongsToMany(User::class, 'orangtua_santri', 'santri_id', 'orangtua_id');
    }
}
