<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateSetoranHafalanTable
{
    public function up()
    {
        Capsule::schema()->create('setoran_hafalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->string('surat');
            $table->integer('ayat_mulai');
            $table->integer('ayat_selesai');
            $table->integer('jumlah_ayat');
            $table->enum('nilai_quality', ['A', 'B', 'C', 'D'])->default('B');
            $table->text('catatan')->nullable();
            $table->date('tgl_setor');
            $table->timestamps();
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('setoran_hafalan');
    }
}