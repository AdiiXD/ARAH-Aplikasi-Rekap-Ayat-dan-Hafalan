<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuranQuote extends Model
{
    protected $table = 'quran_quotes';
    protected $fillable = ['surah_name', 'surah_number', 'ayat_number', 'arabic_text', 'translation', 'theme'];
    public $timestamps = true;

    /**
     * Ambil quote berdasarkan tanggal (menggunakan modulo total quote)
     */
    public static function getDailyQuote()
    {
        $total = self::count();
        if ($total === 0) {
            return null;
        }

        // Gunakan tanggal hari ini sebagai seed untuk indeks yang konsisten setiap hari
        $dayOfYear = (int) date('z'); // 0-365
        $offset = $dayOfYear % $total;
        return self::skip($offset)->first();
    }
}