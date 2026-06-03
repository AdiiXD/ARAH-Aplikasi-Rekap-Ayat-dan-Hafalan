<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateTargetHafalanTable
{
    public function up()
    {
        Capsule::schema()->create('target_hafalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->string('target_ayat');
            $table->date('deadline');
            $table->timestamps();
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('target_hafalan');
    }
}