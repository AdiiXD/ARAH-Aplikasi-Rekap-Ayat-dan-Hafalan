# Hafalan Tracker

Aplikasi manajemen hafalan Al-Qur'an untuk pesantren/madrasah dengan tiga role: **Admin**, **Ustadz**, dan **Orang Tua**.  
Dilengkapi dengan Al-Qur'an digital (baca, dengar, tafsir, tajwid warna), laporan PDF/Excel, notifikasi, statistik hafalan, dan kutipan ayat harian.

---

## ✨ Fitur Utama

### 👑 Admin
- CRUD Ustadz, Santri, Kelas
- Assign santri ke ustadz dan orang tua
- Laporan Excel/PDF seluruh data

### 🧑‍🏫 Ustadz
- Kelola setoran hafalan & murajaah santri binaan
- Target hafalan dengan deadline
- Notifikasi ke orang tua via email & inbox
- Laporan PDF per santri, Excel setoran
- Statistik hafalan mingguan (grafik)

### 👪 Orang Tua
- Pantau progress hafalan anak
- Lihat riwayat setoran & murajaah
- Notifikasi dari ustadz
- Laporan PDF progress anak

### 📖 Al-Qur'an Digital
- Daftar 114 surat + info jumlah ayat & tempat turun
- Baca surat lengkap dengan terjemahan Kemenag
- **Audio per ayat & seluruh surat** (8 qari pilihan)
- **Tajwid warna** (4 warna: Idgham/Ikhfa, Qalqalah, Mad, lainnya)
- **Tafsir Kemenag** per ayat (modal pop-up)
- **Bookmark ayat** favorit
- **Pencarian** surat (nama, nomor, tempat turun)
- **Navigasi cepat** surah:ayat (contoh: `2:255`)

### 📊 Lainnya
- **Quote of the Day** (ayat motivasi berganti setiap hari)
- **Statistik hafalan mingguan** (grafik batang)
- **Reminder deadline target** via cron job
- **Logging aktivitas** (Monolog)

---

## 🛠️ Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP 7.4+ (Native OOP) |
| Database | MySQL |
| ORM | Eloquent (Illuminate\Database) |
| Templating | PHP + Bootstrap 5 |
| Library | phpdotenv, Monolog, Carbon, PHPMailer, Dompdf, PhpSpreadsheet |
| API | Quran.com API, Alquran.cloud API, Quran.gading.dev API |

---

## 📦 Instalasi

1. Clone Repository
```bash
git clone https://github.com/username/hafalan-tracker.git
cd hafalan-tracker

2. Install Composer Dependencies
Pastikan Composer sudah terinstal (composer download).
Kemudian jalankan:

#bash
composer install
#Perintah ini akan mengunduh semua library yang diperlukan (Eloquent, Monolog, PHPMailer, Dompdf, dll.) ke folder vendor/.

3. Konfigurasi Environment (.env)
#bash
cp .env.example .env
#Edit file .env dengan editor teks, sesuaikan nilai berikut:

#.env
# Database
DB_HOST=localhost
DB_NAME=hafalan_tracker
DB_USER=root
DB_PASS=yourpassword

# Mail (opsional, untuk notifikasi email)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@hafalan.com
MAIL_FROM_NAME="Hafalan Tracker"

# Aplikasi
APP_URL=http://localhost/hafalan-tracker/public
APP_TIMEZONE=Asia/Jakarta
Catatan: Jangan commit file .env ke Git (sudah di-ignore). Untuk Gmail, gunakan App Password.

4. Setup Database
#Buat database MySQL:

#bash
mysql -u root -p -e "CREATE DATABASE hafalan_tracker"
#Jalankan migration untuk membuat tabel:

#bash
php public/run-all-migrations.php
#Atau akses via browser: http://localhost/hafalan-tracker/public/run-all-migrations.php

#Jika sukses, akan muncul daftar tabel: users, santri, kelas, setoran_hafalan, dll.

5. Set Permission Folder (Linux/macOS)
#Agar web server bisa menulis log dan file sementara:

#bash
chmod -R 755 logs/
chmod -R 755 public/assets/uploads/   # jika ada upload
#Di Windows, biasanya tidak perlu.

6. Konfigurasi Virtual Host (Opsional, agar URL bersih)
#Contoh untuk Apache (httpd-vhosts.conf):

apache
<VirtualHost *:80>
    ServerName hafalan-tracker.test
    DocumentRoot "C:/xampp/htdocs/hafalan-tracker/public"
    <Directory "C:/xampp/htdocs/hafalan-tracker/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
#Kemudian tambahkan 127.0.0.1 hafalan-tracker.test ke file hosts.
#Tanpa virtual host, aplikasi tetap bisa diakses melalui http://localhost/hafalan-tracker/public.

7. Cron Job (untuk Reminder Deadline Hafalan)
#Buat cron job (Linux/macOS) yang menjalankan script setiap hari jam 7 pagi:

#bash
0 7 * * * php /path/to/project/cron/reminder.php >> /path/to/project/logs/cron.log 2>&1
#Di Windows, gunakan Task Scheduler dengan trigger harian, action program php dengan argumen path ke cron/reminder.php.

#Fungsi cron: memeriksa target hafalan yang deadline ≤ 3 hari, lalu mengirim notifikasi (email & inbox) ke ustadz dan orang tua.

8. Login Default
#Setelah migration dan seeder berjalan, gunakan akun berikut:

Role	Email	Password
Admin	admin@hafalan.com	password
Ustadz	ahmad@hafalan.com	password
Ustadz	fatimah@hafalan.com	password
Orang Tua	budi@example.com	password
Orang Tua	siti@example.com	password
Ganti password setelah login pertama untuk keamanan.

🚀 Cara Menjalankan Aplikasi
Pastikan web server (Apache/XAMPP/Laragon) berjalan.

Akses melalui browser:

Jika pakai virtual host: http://hafalan-tracker.test

Jika tidak: http://localhost/hafalan-tracker/public

Login dengan akun di atas.

Mulai gunakan fitur sesuai role.

📁 Struktur Folder Penting
text
hafalan-tracker/
├── app/
│   ├── Controllers/       # Controller untuk setiap fitur
│   ├── Models/            # Model Eloquent
│   ├── Middleware/        # Auth & Role middleware
│   ├── Helpers/           # QuranHelper, TajwidParser
│   ├── Database/
│   │   ├── Migrations/    # File migration (001_... , 011_...)
│   │   └── Seeders/       # Data awal
├── public/
│   ├── index.php          # Front controller / router
│   ├── assets/            # CSS, JS, images
│   └── .htaccess          # URL rewriting
├── views/
│   ├── layouts/           # main.php, auth.php
│   ├── admin/             # Dashboard & CRUD admin
│   ├── ustadz/            # Dashboard & fitur ustadz
│   ├── orangtua/          # Dashboard & fitur orang tua
│   ├── quran/             # Daftar surat, baca surat, search
│   ├── bookmark/          # Bookmark ayat
│   ├── statistics/        # Grafik mingguan
│   └── tajweed-guide/     # Panduan tajwid warna
├── cron/
│   └── reminder.php       # Script reminder deadline
├── logs/                  # File log (app.log, cron.log)
├── .env                   # Konfigurasi rahasia
├── .gitignore
├── composer.json
└── README.md
🔧 Troubleshooting Umum
Masalah	Solusi
Halaman kosong (error 500)	Aktifkan error reporting di public/index.php: ini_set('display_errors', 1);
Class not found	Jalankan composer dump-autoload
Migration error foreign key	Jalankan SET FOREIGN_KEY_CHECKS=0; di SQL atau gunakan script yang sudah disediakan.
Audio tidak bisa play	Cek konfigurasi qari di QuranHelper.php, pastikan API alquran.cloud dapat diakses.
Login gagal	Pastikan seeder sudah dijalankan, cek tabel users di database.


📄 Lisensi
MIT License – silakan digunakan, dimodifikasi, dan didistribusikan untuk keperluan pendidikan.


🙏 Sumber API
Quran.com API – teks, terjemahan, tajwid

Alquran.cloud API – audio murottal

Quran Gading API – tafsir Kemenag


👨‍💻 Pengembang
Dibuat oleh [ AdiXD ] untuk memudahkan manajemen hafalan Al-Qur'an.
Kontak: [adirachmat223@gmail.com]s

text

---

Dengan `README.md` ini, setiap orang yang meng-clone proyek dapat menginstal dan menjalankannya tanpa kebingungan. Semua langkah dari Composer, `.env`, migration, permission, virtual host, hingga cron job sudah dijelaskan secara lengkap.
