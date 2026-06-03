<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateQuranQuotesTable
{
    public function up()
    {
        Capsule::schema()->create('quran_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('surah_name');
            $table->integer('surah_number')->nullable();
            $table->integer('ayat_number');
            $table->text('arabic_text');
            $table->text('translation');
            $table->string('theme')->nullable();
            $table->timestamps();
        });

        // Insert data awal
        $this->seed();
    }

    private function seed()
    {
        $quotes = [
            ['Al-Fatihah', 1, 1, 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ', 'Dengan nama Allah Yang Maha Pengasih, Maha Penyayang.', 'pembuka'],
            ['Al-Baqarah', 2, 45, 'إِنَّ اللَّهَ مَعَ الصَّابِرِينَ', 'Sesungguhnya Allah beserta orang-orang yang sabar.', 'sabar'],
            ['Al-Baqarah', 2, 152, 'فَاذْكُرُونِي أَذْكُرْكُمْ وَاشْكُرُوا لِي وَلَا تَكْفُرُونِ', 'Maka ingatlah kepada-Ku, Aku pun akan ingat kepadamu. Bersyukurlah kepada-Ku dan janganlah kamu ingkar.', 'syukur'],
            ['Al-Baqarah', 2, 286, 'لَا يُكَلِّفُ اللَّهُ نَفْسًا إِلَّا وُسْعَهَا', 'Allah tidak membebani seseorang melainkan sesuai dengan kesanggupannya.', 'motivasi'],
            ['Ali Imran', 3, 159, 'إِنَّ اللَّهَ يُحِبُّ الْمُتَوَكِّلِينَ', 'Sesungguhnya Allah menyukai orang-orang yang bertawakal.', 'tawakal'],
            ['Ali Imran', 3, 185, 'فَمَنْ زُحْزِحَ عَنِ النَّارِ وَأُدْخِلَ الْجَنَّةَ فَقَدْ فَازَ', 'Siapa yang dijauhkan dari neraka dan dimasukkan ke dalam surga, sungguh dia telah beruntung.', 'motivasi'],
            ['An-Nisa', 4, 1, 'يَا أَيُّهَا النَّاسُ اتَّقُوا رَبَّكُمُ', 'Wahai manusia! Bertakwalah kepada Tuhanmu.', 'takwa'],
            ['Al-Maidah', 5, 8, 'اعْدِلُوا هُوَ أَقْرَبُ لِلتَّقْوَىٰ', 'Berlaku adillah, karena adil lebih dekat kepada takwa.', 'keadilan'],
            ['At-Tawbah', 9, 128, 'بِالْمُؤْمِنِينَ رَءُوفٌ رَّحِيمٌ', 'Sangat belas kasih dan penyayang terhadap orang-orang mukmin.', 'kasih sayang'],
            ['Yunus', 10, 62, 'أَلَا إِنَّ أَوْلِيَاءَ اللَّهِ لَا خَوْفٌ عَلَيْهِمْ وَلَا هُمْ يَحْزَنُونَ', 'Ingatlah, sesungguhnya wali-wali Allah tidak ada rasa takut pada mereka dan mereka tidak bersedih hati.', 'motivasi'],
            ['Ar-Ra\'d', 13, 28, 'أَلَا بِذِكْرِ اللَّهِ تَطْمَئِنُّ الْقُلُوبُ', 'Ingatlah, hanya dengan mengingat Allah hati menjadi tenteram.', 'ketenangan'],
            ['Ibrahim', 14, 7, 'لَئِن شَكَرْتُمْ لَأَزِيدَنَّكُمْ', 'Sesungguhnya jika kamu bersyukur, niscaya Aku akan menambah (nikmat) kepadamu.', 'syukur'],
            ['An-Nahl', 16, 125, 'ادْعُ إِلَىٰ سَبِيلِ رَبِّكَ بِالْحِكْمَةِ وَالْمَوْعِظَةِ الْحَسَنَةِ', 'Serulah ke jalan Tuhanmu dengan hikmah dan pelajaran yang baik.', 'dakwah'],
            ['Al-Isra', 17, 80, 'رَبِّ أَدْخِلْنِي مُدْخَلَ صِدْقٍ', 'Ya Tuhan, masukkanlah aku dengan masuk yang benar.', 'doa'],
            ['Al-Kahf', 18, 110, 'فَمَن كَانَ يَرْجُو لِقَاءَ رَبِّهِ فَلْيَعْمَلْ عَمَلًا صَالِحًا', 'Barang siapa mengharap pertemuan dengan Tuhannya, maka hendaklah dia mengerjakan kebajikan.', 'amal'],
            ['Maryam', 19, 96, 'إِنَّ الَّذِينَ آمَنُوا وَعَمِلُوا الصَّالِحَاتِ سَيَجْعَلُ لَهُمُ الرَّحْمَٰنُ وُدًّا', 'Sungguh, orang-orang yang beriman dan beramal shaleh nanti Allah akan menanamkan rasa kasih sayang pada mereka.', 'kasih sayang'],
            ['Thaha', 20, 46, 'إِنَّنِي مَعَكُمَا أَسْمَعُ وَأَرَىٰ', 'Sesungguhnya Aku bersama kamu berdua, Aku mendengar dan melihat.', 'motivasi'],
            ['Al-Hajj', 22, 77, 'افْعَلُوا الْخَيْرَ لَعَلَّكُمْ تُفْلِحُونَ', 'Lakukanlah kebaikan, agar kamu beruntung.', 'amal'],
        ];

        foreach ($quotes as $q) {
            Capsule::table('quran_quotes')->insert([
                'surah_name' => $q[0],
                'surah_number' => $q[1],
                'ayat_number' => $q[2],
                'arabic_text' => $q[3],
                'translation' => $q[4],
                'theme' => $q[5],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('quran_quotes');
    }
}