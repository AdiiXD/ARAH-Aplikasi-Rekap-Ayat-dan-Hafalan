<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetoranHafalan extends Model
{
    protected $table = 'setoran_hafalan';
    protected $fillable = ['santri_id', 'surat', 'ayat_mulai', 'ayat_selesai', 'jumlah_ayat', 'nilai_quality', 'catatan', 'tgl_setor'];
    public $timestamps = true;

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}