<?php
use App\Helpers\TajwidParser;

$chapterInfo = $chapterInfo ?? null;
$verses = $verses ?? [];
$reciters = $reciters ?? [];
$selectedReciter = $selectedReciter ?? 'ar.alafasy';
$showTajwid = $showTajwid ?? false;
?>
<div class="card-custom p-4" id="quranContainer">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-book"></i> <?= htmlspecialchars($chapterInfo['name_simple'] ?? 'Surat') ?></h3>
        <div>
            <a href="index.php?action=quran" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
        </div>
    </div>
    
    <div class="text-center mb-4" id="surahHeader">
        <h2><?= htmlspecialchars($chapterInfo['name_arabic'] ?? '') ?></h2>
        <p class="text-muted"><?= htmlspecialchars($chapterInfo['name_transliteration'] ?? '') ?></p>
        <p><strong>Jumlah Ayat: <?= $chapterInfo['verses_count'] ?? 0 ?></strong> | <strong>Tempat Turun: <?= ($chapterInfo['revelation_place'] ?? '') == 'makkah' ? 'Makkah' : 'Madinah' ?></strong></p>
    </div>

    <?php if (empty($verses)): ?>
        <div class="alert alert-warning">Ayat tidak tersedia.</div>
    <?php else: ?>
        <?php foreach ($verses as $index => $verse): ?>
        <?php 
            $audioUrl = $verse['audio_url'] ?? '';
            $verseNumber = $verse['verse_number'] ?? ($index + 1);
            
            // Teks Arab dengan atau tanpa tajwid
            if ($showTajwid && !empty($verse['text_uthmani_tajweed'])) {
                $arabicText = TajwidParser::parse($verse['text_uthmani_tajweed']);
            } else {
                $arabicText = $verse['text_uthmani'] ?? '';
            }
            
            // Bersihkan terjemahan dari HTML
            $rawTranslation = $verse['translations'][0]['text'] ?? '';
            $cleanTranslation = strip_tags($rawTranslation);
            $cleanTranslation = preg_replace('/\s+/', ' ', $cleanTranslation);
            $cleanTranslation = trim($cleanTranslation);
        ?>
        <div class="verse-card mb-4 p-3 border rounded" data-verse-number="<?= $verseNumber ?>" data-audio-url="<?= htmlspecialchars($audioUrl) ?>">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <strong class="badge bg-maroon p-2">Ayat <?= $verseNumber ?></strong>
                    <?php if (!empty($audioUrl)): ?>
                    <button class="btn btn-sm btn-outline-maroon btn-play-verse ms-2" data-verse="<?= $verseNumber ?>">
                        <i class="bi bi-play-fill"></i> Play
                    </button>
                    <?php endif; ?>
                    <button class="btn btn-sm btn-outline-info btn-tafsir ms-2" data-surah="<?= $chapterInfo['id'] ?? '' ?>" data-ayat="<?= $verseNumber ?>">
                        <i class="bi bi-book"></i> Tafsir
                    </button>
                    <!-- Tombol Bookmark -->
                    <form method="POST" action="index.php?action=bookmark/add" class="d-inline">
                        <input type="hidden" name="surah" value="<?= $chapterInfo['id'] ?? '' ?>">
                        <input type="hidden" name="ayat" value="<?= $verseNumber ?>">
                        <input type="hidden" name="surah_name" value="<?= htmlspecialchars($chapterInfo['name_simple'] ?? '') ?>">
                        <button type="submit" class="btn btn-sm btn-outline-warning ms-2" title="Bookmark ayat ini">
                            <i class="bi bi-bookmark-plus"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="arabic-text text-end mt-3" style="font-size: 1.5rem; line-height: 2rem; direction: rtl;">
                <?= $arabicText ?>
            </div>
            <div class="translation-text mt-2">
                <em><?= htmlspecialchars($cleanTranslation ?: 'Terjemahan tidak tersedia.') ?></em>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Tafsir -->
<div class="modal fade" id="tafsirModal" tabindex="-1" aria-labelledby="tafsirModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #4A1D2E; color: white;">
                <h5 class="modal-title" id="tafsirModalLabel"><i class="bi bi-book"></i> Tafsir Al-Qur'an</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="tafsirModalBody"><div class="text-center spinner-border text-maroon"></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>

<!-- Floating Control Panel -->
<div id="floatingControls" class="fixed-bottom mb-3 me-3 d-flex justify-content-end" style="z-index: 1050; pointer-events: none;">
    <div class="bg-white rounded-4 shadow-lg p-2 d-flex gap-2 align-items-center" style="pointer-events: auto; backdrop-filter: blur(8px); background-color: rgba(255, 249, 239, 0.95); border: 1px solid #E6DDD0;">
        <button id="floatPlaySurah" class="btn btn-maroon btn-sm rounded-circle control-btn" title="Play Seluruh Surah"><i class="bi bi-play-fill"></i></button>
        <button id="floatStopSurah" class="btn btn-secondary btn-sm rounded-circle control-btn" title="Stop Surah" style="display: none;"><i class="bi bi-stop-fill"></i></button>
        <select id="floatReciter" class="form-select form-select-sm w-auto" style="border-radius: 20px; font-size: 0.8rem;">
            <?php foreach ($reciters as $key => $name): ?>
                <option value="<?= $key ?>" <?= $selectedReciter == $key ? 'selected' : '' ?>><?= htmlspecialchars($name) ?></option>
            <?php endforeach; ?>
        </select>
        <button id="toggleTranslation" class="btn btn-outline-maroon btn-sm rounded-pill control-btn" style="min-width: 50px;">Terj.</button>
        <button id="toggleTajwid" class="btn btn-<?= $showTajwid ? 'maroon' : 'outline-maroon' ?> btn-sm rounded-pill control-btn" style="min-width: 50px;">Tajwid</button>
        <audio id="globalAudio" style="display: none;"></audio>
    </div>
</div>

<style>
    /* Floating button styling */
    .control-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: none;
    }
    .control-btn:active {
        transform: scale(1);
        box-shadow: none;
    }
    .btn-outline-maroon.rounded-pill {
        width: auto;
        padding-left: 12px;
        padding-right: 12px;
    }
    @media (max-width: 768px) {
        .fixed-bottom .bg-white {
            padding: 0.5rem !important;
        }
        .control-btn {
            width: 32px;
            height: 32px;
        }
        .btn-outline-maroon.rounded-pill {
            font-size: 0.75rem;
            padding-left: 8px;
            padding-right: 8px;
            min-width: 45px;
        }
        .form-select-sm {
            font-size: 0.7rem;
            padding: 0.2rem 0.3rem;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const globalAudio = document.getElementById('globalAudio');
    const floatPlaySurah = document.getElementById('floatPlaySurah');
    const floatStopSurah = document.getElementById('floatStopSurah');
    const floatReciter = document.getElementById('floatReciter');
    const toggleTranslation = document.getElementById('toggleTranslation');
    const toggleTajwid = document.getElementById('toggleTajwid');

    // Data surah (untuk log)
    const currentSurahId = <?= $chapterInfo['id'] ?? 0 ?>;

    // Kumpulkan data ayat
    const verseElements = Array.from(document.querySelectorAll('.verse-card'));
    const verseData = verseElements.map(el => {
        return {
            element: el,
            verseNumber: parseInt(el.getAttribute('data-verse-number')),
            audioUrl: el.getAttribute('data-audio-url')
        };
    }).filter(v => v.audioUrl && v.audioUrl !== '');
    const totalVerses = verseData.length;

    let isPlayingSurah = false;
    let currentSurahIndex = 0;
    let currentPlayingVerse = null;
    let currentVerseTime = 0;
    let translationVisible = true;

    // Fungsi untuk mengirim log ke server saat audio mulai diputar
    function logPlay(surah, ayat) {
        fetch('index.php?action=quran/log-play', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'surah=' + surah + '&ayat=' + ayat
        }).catch(e => console.error('Gagal kirim log:', e));
    }

    function scrollToVerse(verseNumber) {
        const el = document.querySelector(`.verse-card[data-verse-number="${verseNumber}"]`);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function playVerseByIndex(index, autoPlay = true, startTime = 0) {
        if (index >= totalVerses) {
            if (isPlayingSurah) { alert('Selesai memutar seluruh surah.'); stopSurahPlayback(); }
            return false;
        }
        const verse = verseData[index];
        if (!verse.audioUrl) return false;
        
        scrollToVerse(verse.verseNumber);
        
        if (globalAudio.src !== verse.audioUrl) {
            globalAudio.src = verse.audioUrl;
        }
        globalAudio.currentTime = startTime;
        
        if (autoPlay) {
            globalAudio.play().catch(e => console.error('Play error:', e));
            // Catat log bahwa ayat ini diputar
            logPlay(currentSurahId, verse.verseNumber);
        }
        
        currentPlayingVerse = verse.verseNumber;
        updatePlayButtons(verse.verseNumber, 'pause');
        return true;
    }

    function playNextInSurah() {
        if (!isPlayingSurah) return;
        if (currentSurahIndex >= totalVerses) {
            stopSurahPlayback();
            alert('Selesai memutar seluruh surah.');
            return;
        }
        playVerseByIndex(currentSurahIndex, true, 0);
        globalAudio.onended = function() {
            if (isPlayingSurah) { currentSurahIndex++; playNextInSurah(); }
        };
    }

    function startSurah() {
        if (totalVerses === 0) return;
        if (currentPlayingVerse !== null && !globalAudio.paused) globalAudio.pause();
        stopSurahPlayback();
        isPlayingSurah = true;
        currentSurahIndex = 0;
        floatPlaySurah.style.display = 'none';
        floatStopSurah.style.display = 'inline-flex';
        playNextInSurah();
    }

    function stopSurahPlayback() {
        if (isPlayingSurah) {
            isPlayingSurah = false;
            globalAudio.pause();
            globalAudio.onended = null;
            currentSurahIndex = 0;
            floatPlaySurah.style.display = 'inline-flex';
            floatStopSurah.style.display = 'none';
        }
        if (currentPlayingVerse !== null) {
            updatePlayButtons(currentPlayingVerse, 'play');
            currentPlayingVerse = null;
            currentVerseTime = 0;
        }
    }

    function playSingleVerse(verseNumber) {
        const index = verseData.findIndex(v => v.verseNumber === verseNumber);
        if (index === -1) return;
        if (isPlayingSurah) stopSurahPlayback();
        if (currentPlayingVerse === verseNumber && globalAudio.paused) {
            globalAudio.currentTime = currentVerseTime;
            globalAudio.play().catch(e => console.error(e));
            updatePlayButtons(verseNumber, 'pause');
            // Catat log
            logPlay(currentSurahId, verseNumber);
            return;
        }
        if (currentPlayingVerse !== null && !globalAudio.paused) {
            globalAudio.pause();
            currentVerseTime = globalAudio.currentTime;
            updatePlayButtons(currentPlayingVerse, 'play');
        }
        playVerseByIndex(index, true, 0);
    }

    function pauseSingleVerse() {
        if (currentPlayingVerse !== null && !globalAudio.paused) {
            globalAudio.pause();
            currentVerseTime = globalAudio.currentTime;
            updatePlayButtons(currentPlayingVerse, 'play');
        }
    }

    function updatePlayButtons(activeVerse, state) {
        document.querySelectorAll('.btn-play-verse').forEach(btn => {
            const v = parseInt(btn.getAttribute('data-verse'));
            if (v === activeVerse) {
                if (state === 'pause') {
                    btn.innerHTML = '<i class="bi bi-pause-fill"></i> Pause';
                    btn.classList.remove('btn-outline-maroon');
                    btn.classList.add('btn-warning');
                } else {
                    btn.innerHTML = '<i class="bi bi-play-fill"></i> Play';
                    btn.classList.remove('btn-warning');
                    btn.classList.add('btn-outline-maroon');
                }
            } else {
                btn.innerHTML = '<i class="bi bi-play-fill"></i> Play';
                btn.classList.remove('btn-warning');
                btn.classList.add('btn-outline-maroon');
            }
        });
    }

    // Event listeners untuk floating panel
    floatPlaySurah.addEventListener('click', startSurah);
    floatStopSurah.addEventListener('click', stopSurahPlayback);

    floatReciter.addEventListener('change', function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href;
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'reciter';
        input.value = this.value;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    });

    toggleTranslation.addEventListener('click', function() {
        translationVisible = !translationVisible;
        document.querySelectorAll('.translation-text').forEach(el => el.style.display = translationVisible ? 'block' : 'none');
        if (translationVisible) {
            toggleTranslation.classList.remove('btn-outline-maroon');
            toggleTranslation.classList.add('btn-maroon');
        } else {
            toggleTranslation.classList.remove('btn-maroon');
            toggleTranslation.classList.add('btn-outline-maroon');
        }
    });

    toggleTajwid.addEventListener('click', function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href;
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'toggle_tajwid';
        input.value = '1';
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    });

    // Tombol play per ayat
    document.querySelectorAll('.btn-play-verse').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const verseNum = parseInt(btn.getAttribute('data-verse'));
            const isPlayingThis = (currentPlayingVerse === verseNum && !globalAudio.paused);
            if (isPlayingThis) pauseSingleVerse();
            else playSingleVerse(verseNum);
        });
    });

    globalAudio.addEventListener('ended', () => {
        if (!isPlayingSurah && currentPlayingVerse !== null) {
            updatePlayButtons(currentPlayingVerse, 'play');
            currentPlayingVerse = null;
            currentVerseTime = 0;
        }
    });

    setInterval(() => {
        if (currentPlayingVerse !== null && !globalAudio.paused) {
            currentVerseTime = globalAudio.currentTime;
        }
    }, 500);

    // Tafsir modal
    const tafsirModal = new bootstrap.Modal(document.getElementById('tafsirModal'));
    const tafsirModalBody = document.getElementById('tafsirModalBody');
    document.querySelectorAll('.btn-tafsir').forEach(btn => {
        btn.addEventListener('click', function() {
            const surah = this.getAttribute('data-surah');
            const ayat = this.getAttribute('data-ayat');
            tafsirModalBody.innerHTML = `<div class="text-center"><div class="spinner-border text-maroon"></div><p>Memuat tafsir...</p></div>`;
            tafsirModal.show();
            fetch(`index.php?action=quran/tafsir&surah=${surah}&ayat=${ayat}`)
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'success') {
                        tafsirModalBody.innerHTML = `<div class="tafsir-content"><span class="badge bg-maroon">QS. <?= htmlspecialchars($chapterInfo['name_simple'] ?? '') ?>: ${ayat}</span><p class="mt-2" style="text-align:justify">${d.data}</p></div>`;
                    } else {
                        tafsirModalBody.innerHTML = `<div class="alert alert-warning">${d.message || 'Tafsir tidak tersedia'}</div>`;
                    }
                })
                .catch(() => tafsirModalBody.innerHTML = `<div class="alert alert-danger">Gagal memuat tafsir</div>`);
        });
    });
});
</script>