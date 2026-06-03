<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = ['nama_kelas', 'deskripsi'];
    public $timestamps = true;

    public function santris()
    {
        return $this->hasMany(Santri::class);
    }
}