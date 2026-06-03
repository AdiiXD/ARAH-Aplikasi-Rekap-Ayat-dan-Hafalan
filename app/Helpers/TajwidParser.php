<?php

namespace App\Helpers;

class TajwidParser
{
    /**
     * Mapping class ke warna (prioritas: hijau, merah, biru, oranye)
     */
    private static function getColorForClass(string $class): string
    {
        $class = strtolower(trim($class));

        // Hijau untuk Idgham, Ikhfa, Iqlab
        if (preg_match('/(ikhfa|idgham|iqlab)/', $class)) {
            return '#28a745';
        }
        // Merah untuk Qalqalah
        if (preg_match('/qalqalah/', $class)) {
            return '#dc3545';
        }
        // Biru untuk Mad
        if (preg_match('/mad/', $class)) {
            return '#007bff';
        }
        // Oranye untuk semua hukum lainnya (termasuk ham_wasl, laam_shamsiyah, ra_, saktah, tashil, dll)
        return '#e67e22';
    }

    public static function parse(string $text): string
    {
        if (empty($text)) {
            return '';
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div>' . $text . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $tajweedTags = $xpath->query('//tajweed');

        for ($i = $tajweedTags->length - 1; $i >= 0; $i--) {
            $tag = $tajweedTags->item($i);
            if (!$tag instanceof \DOMElement) continue;

            $classAttr = $tag->getAttribute('class');
            $color = self::getColorForClass($classAttr);

            $span = $dom->createElement('span');
            $span->setAttribute('style', "color: {$color}; font-weight: 500;");
            $span->setAttribute('data-tajwid', $classAttr);

            while ($tag->firstChild) {
                $span->appendChild($tag->firstChild);
            }
            $tag->parentNode->replaceChild($span, $tag);
        }

        $div = $xpath->query('//div')->item(0);
        $innerHtml = '';
        if ($div) {
            foreach ($div->childNodes as $child) {
                $innerHtml .= $dom->saveHTML($child);
            }
        }

        return $innerHtml;
    }
}