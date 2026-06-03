<?php

namespace App\Helpers;

use Exception;

class QuranHelper
{
    private $baseUrl = 'https://api.quran.com/api/v4';
    private $translationId = 33; // Kemenag RI

    // Daftar qari yang valid dan sudah diuji
    private $availableReciters = [
        'ar.alafasy'                => 'Mishary Rashid Alafasy',
        'ar.abdulbasitmurattal'     => 'Abdul Basit (Murattal)',
        'ar.abdurrahmaansudais'     => 'Abdurrahmaan As-Sudais',
        'ar.mahermuaiqly'           => 'Maher Al Muaiqly',
        'ar.husary'                 => 'Husary',
        'ar.ahmedajamy'             => 'Ahmed Ajamy',
        'ar.shaatree'               => 'Abu Bakr Ash-Shaatree',
        'ar.minshawi'               => 'Minshawi',
    ];

    public function getAvailableReciters(): array
    {
        return $this->availableReciters;
    }

    public function getSelectedReciter(): string
    {
        return $_SESSION['selected_reciter'] ?? 'ar.alafasy';
    }

    public function setSelectedReciter(string $reciterId): void
    {
        if (array_key_exists($reciterId, $this->availableReciters)) {
            $_SESSION['selected_reciter'] = $reciterId;
        }
    }

    public function getChapters(): array
    {
        $url = $this->baseUrl . '/chapters';
        $response = $this->makeRequest($url);
        return $response['chapters'] ?? [];
    }

    public function getVersesByChapter(int $chapterNumber, int $perPage = 300): array
    {
        $url = $this->baseUrl . "/verses/by_chapter/{$chapterNumber}";
        $params = [
            'language' => 'id',
            'words' => 'false',
            'translations' => $this->translationId,
            'fields' => 'text_uthmani,verse_key',
            'per_page' => $perPage,
        ];
        $response = $this->makeRequest($url, $params);
        $verses = $response['verses'] ?? [];

        $selectedReciter = $this->getSelectedReciter();
        $audioApiUrl = "https://api.alquran.cloud/v1/surah/{$chapterNumber}/{$selectedReciter}";
        try {
            $audioResponse = $this->makeRequest($audioApiUrl);
            if (isset($audioResponse['data']['ayahs'])) {
                foreach ($verses as $index => &$verse) {
                    $ayahNumber = $verse['verse_number'] ?? ($index + 1);
                    $verse['audio_url'] = $audioResponse['data']['ayahs'][$ayahNumber - 1]['audio'] ?? null;
                }
            }
        } catch (Exception $e) {
            error_log("Error fetching audio: " . $e->getMessage());
        }

        return $verses;
    }

    /**
     * Mendapatkan ayat per surat dengan teks biasa dan teks tajwid
     *
     * @param int $chapterNumber
     * @param int $perPage
     * @return array
     */
    public function getVersesWithTajweed(int $chapterNumber, int $perPage = 300): array
    {
        // Ambil ayat dasar (teks biasa, terjemahan, audio)
        $verses = $this->getVersesByChapter($chapterNumber, $perPage);

        // Ambil teks tajwid dari API jika tersedia
        $url = $this->baseUrl . "/verses/by_chapter/{$chapterNumber}";
        $params = [
            'fields' => 'text_uthmani_tajweed',
            'per_page' => $perPage,
        ];

        try {
            $response = $this->makeRequest($url, $params);
            $tajweedVerses = $response['verses'] ?? [];
            foreach ($verses as $index => &$verse) {
                $verse['text_uthmani_tajweed'] = $tajweedVerses[$index]['text_uthmani_tajweed'] ?? null;
            }
        } catch (\Exception $e) {
            error_log("Error fetching tajweed text: " . $e->getMessage());
            // Fallback: beri nilai null agar teks biasa yang digunakan
            foreach ($verses as &$verse) {
                $verse['text_uthmani_tajweed'] = null;
            }
        }

        return $verses;
    }

    public function getChapterInfo(int $chapterId): ?array
    {
        $chapters = $this->getChapters();
        foreach ($chapters as $chapter) {
            if ($chapter['id'] == $chapterId) {
                return $chapter;
            }
        }
        return null;
    }

    public function getTafsir(int $surah, int $ayat): ?string
    {
        $apiUrl = "https://api.quran.gading.dev/surah/{$surah}/{$ayat}";
        try {
            $response = $this->makeRequest($apiUrl);
            if (isset($response['data']['tafsir']['id']['short'])) {
                $tafsir = $response['data']['tafsir']['id']['short'];
                $tafsir = strip_tags($tafsir);
                $tafsir = preg_replace('/\s+/', ' ', $tafsir);
                return trim($tafsir);
            }
        } catch (Exception $e) {
            error_log("Tafsir error: " . $e->getMessage());
        }
        return null;
    }

    public function makeRequest(string $url, array $params = []): array
    {
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new Exception("CURL Error: " . $curlError);
        }
        if ($httpCode !== 200) {
            throw new Exception("API Error: HTTP " . $httpCode);
        }

        return json_decode($response, true);
    }
}
