<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetoranMurajaah extends Model
{
    protected $table = 'setoran_murajaah';
    protected $fillable = ['santri_id', 'surat', 'ayat', 'jumlah_ulangan', 'tgl_murajaah'];
    public $timestamps = true;

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}