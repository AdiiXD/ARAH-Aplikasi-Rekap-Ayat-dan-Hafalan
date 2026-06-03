<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetHafalan extends Model
{
    protected $table = 'target_hafalan';
    protected $fillable = ['santri_id', 'target_ayat', 'deadline'];
    public $timestamps = true;

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}