<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class AddSurahNumberToQuranQuotes
{
    public function up()
    {
        if (!Capsule::schema()->hasColumn('quran_quotes', 'surah_number')) {
            Capsule::schema()->table('quran_quotes', function (Blueprint $table) {
                $table->integer('surah_number')->nullable()->after('surah_name');
            });
            
            // Update data existing dengan mapping (optional)
            $map = [
                'Al-Fatihah' => 1,
                'Al-Baqarah' => 2,
                'Ali Imran' => 3,
                'An-Nisa' => 4,
                'Al-Maidah' => 5,
                'At-Tawbah' => 9,
                'Yunus' => 10,
                'Ar-Ra\'d' => 13,
                'Ibrahim' => 14,
                'An-Nahl' => 16,
                'Al-Isra' => 17,
                'Al-Kahf' => 18,
                'Maryam' => 19,
                'Thaha' => 20,
                'Al-Hajj' => 22,
            ];
            foreach ($map as $name => $number) {
                Capsule::table('quran_quotes')
                    ->where('surah_name', $name)
                    ->update(['surah_number' => $number]);
            }
        }
    }

    public function down()
    {
        Capsule::schema()->table('quran_quotes', function (Blueprint $table) {
            $table->dropColumn('surah_number');
        });
    }
}