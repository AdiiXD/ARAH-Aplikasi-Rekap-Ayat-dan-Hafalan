<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class AddNisNicknameToSantriTable
{
    public function up()
    {
        if (!Capsule::schema()->hasColumn('santri', 'nis')) {
            Capsule::schema()->table('santri', function (Blueprint $table) {
                $table->string('nis', 20)->unique()->nullable()->after('id');
                $table->string('nickname')->nullable()->after('nama');
            });
        }
    }

    public function down()
    {
        if (Capsule::schema()->hasColumn('santri', 'nis')) {
            Capsule::schema()->table('santri', function (Blueprint $table) {
                $table->dropColumn(['nis', 'nickname']);
            });
        }
    }
}