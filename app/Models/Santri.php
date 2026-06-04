<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    protected $table = 'santri';
    protected $fillable = [
        'nis',
        'nama',
        'nickname',
        'tanggal_lahir',
        'tahun_masuk',
        'ustadz_id',
        'kelas_id'
    ];
    public $timestamps = true;

    // Relasi ke ustadz (pengguna dengan role ustadz)
    public function ustadz()
    {
        return $this->belongsTo(User::class, 'ustadz_id');
    }

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke orang tua (many-to-many)
    public function orangTua()
    {
        return $this->belongsToMany(User::class, 'orangtua_santri', 'santri_id', 'orangtua_id');
    }

    // Relasi ke target hafalan
    public function targetHafalan()
    {
        return $this->hasMany(TargetHafalan::class);
    }

    // Relasi ke setoran hafalan
    public function setoranHafalan()
    {
        return $this->hasMany(SetoranHafalan::class);
    }

    // Relasi ke setoran murajaah
    public function setoranMurajaah()
    {
        return $this->hasMany(SetoranMurajaah::class);
    }
}