<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateOrangtuaSantriTable
{
    public function up()
    {
        Capsule::schema()->create('orangtua_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orangtua_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('orangtua_santri');
    }
}