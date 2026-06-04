<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangtuaSantri extends Model
{
    protected $table = 'orangtua_santri';
    protected $fillable = ['orangtua_id', 'santri_id'];
    public $timestamps = true;
}