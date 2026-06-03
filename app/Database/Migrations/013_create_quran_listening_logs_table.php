<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateQuranListeningLogsTable
{
    public function up()
    {
        Capsule::schema()->create('quran_listening_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('surah_number');
            $table->integer('ayat_number');
            $table->timestamp('played_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('quran_listening_logs');
    }
}