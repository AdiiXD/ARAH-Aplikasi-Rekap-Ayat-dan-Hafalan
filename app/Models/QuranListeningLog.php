<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuranListeningLog extends Model
{
    protected $table = 'quran_listening_logs';
    protected $fillable = ['user_id', 'surah_number', 'ayat_number', 'played_at'];
    public $timestamps = true;

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Hitung total ayat yang diputar user hari ini
    public static function todayCount(int $userId): int
    {
        return self::where('user_id', $userId)
            ->whereDate('played_at', date('Y-m-d'))
            ->count();
    }

    // Dapatkan detail per surah untuk hari ini
    public static function todayDetails(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('user_id', $userId)
            ->whereDate('played_at', date('Y-m-d'))
            ->selectRaw('surah_number, COUNT(*) as total')
            ->groupBy('surah_number')
            ->orderBy('total', 'desc')
            ->get();
    }
}