<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateSetoranMurajaahTable
{
    public function up()
    {
        Capsule::schema()->create('setoran_murajaah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->string('surat');
            $table->integer('ayat');
            $table->integer('jumlah_ulangan');
            $table->date('tgl_murajaah');
            $table->timestamps();
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('setoran_murajaah');
    }
}