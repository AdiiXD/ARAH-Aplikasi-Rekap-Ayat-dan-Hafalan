<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TargetHafalan;
use App\Models\SetoranHafalan;
use App\Models\SetoranMurajaah;
use App\Models\User;
use App\Models\Kelas;

class Santri extends Model
{
    protected $table = 'santri';
    protected $fillable = ['nama', 'tanggal_lahir', 'tahun_masuk', 'ustadz_id', 'kelas_id'];
    public $timestamps = true;

    public function ustadz()
    {
        return $this->belongsTo(User::class, 'ustadz_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function orangTua()
    {
        return $this->belongsToMany(User::class, 'orangtua_santri', 'santri_id', 'orangtua_id');
    }

    public function targetHafalan()
    {
        return $this->hasMany(TargetHafalan::class);
    }

    public function setoranHafalan()
    {
        return $this->hasMany(SetoranHafalan::class);
    }

    public function setoranMurajaah()
    {
        return $this->hasMany(SetoranMurajaah::class);
    }
}
