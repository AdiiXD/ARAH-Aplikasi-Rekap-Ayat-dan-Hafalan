<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Carbon\Carbon;

class DatabaseSeeder
{
    public function run()
    {
        // Matikan foreign key checks sementara
        Capsule::statement('SET FOREIGN_KEY_CHECKS=0');

        // Truncate semua tabel (urutan bebas karena foreign key dimatikan)
        Capsule::table('orangtua_santri')->truncate();
        Capsule::table('setoran_hafalan')->truncate();
        Capsule::table('setoran_murajaah')->truncate();
        Capsule::table('target_hafalan')->truncate();
        Capsule::table('notifikasi')->truncate();
        Capsule::table('logs')->truncate();
        Capsule::table('santri')->truncate();
        Capsule::table('kelas')->truncate();
        Capsule::table('users')->truncate();

        // Aktifkan kembali foreign key checks
        Capsule::statement('SET FOREIGN_KEY_CHECKS=1');

        // Insert Kelas
        $kelasIds = [];
        $kelas = ['Tahfidz Pemula', 'Tahfidz Madya', 'Tahfidz Mahir'];
        foreach ($kelas as $k) {
            $id = Capsule::table('kelas')->insertGetId([
                'nama_kelas' => $k,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $kelasIds[] = $id;
        }

        // Insert Users: Admin
        $adminId = Capsule::table('users')->insertGetId([
            'name' => 'Admin Utama',
            'email' => 'admin@hafalan.com',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'admin',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // Insert Ustadz
        $ustadzIds = [];
        $ustadzData = [
            ['Ustadz Ahmad', 'ahmad@hafalan.com'],
            ['Ustadz Fatimah', 'fatimah@hafalan.com']
        ];
        foreach ($ustadzData as $u) {
            $id = Capsule::table('users')->insertGetId([
                'name' => $u[0],
                'email' => $u[1],
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'ustadz',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $ustadzIds[] = $id;
        }

        // Insert Orang Tua
        $ortuIds = [];
        $ortuData = [
            ['Budi Santoso', 'budi@example.com'],
            ['Siti Aminah', 'siti@example.com']
        ];
        foreach ($ortuData as $o) {
            $id = Capsule::table('users')->insertGetId([
                'name' => $o[0],
                'email' => $o[1],
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'orangtua',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $ortuIds[] = $id;
        }

        // Insert Santri
        $santriData = [
            ['Ali Imran', '2015-05-10', 2023, $ustadzIds[0], $kelasIds[0]],
            ['Sofia Rahma', '2016-08-22', 2024, $ustadzIds[0], $kelasIds[1]],
            ['Zaki Firmansyah', '2014-12-01', 2022, $ustadzIds[1], $kelasIds[2]]
        ];
        $santriIds = [];
        foreach ($santriData as $s) {
            $id = Capsule::table('santri')->insertGetId([
                'nama' => $s[0],
                'tanggal_lahir' => $s[1],
                'tahun_masuk' => $s[2],
                'ustadz_id' => $s[3],
                'kelas_id' => $s[4],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $santriIds[] = $id;
        }

        // Assign Orang Tua ke Santri (many-to-many)
        Capsule::table('orangtua_santri')->insert([
            ['orangtua_id' => $ortuIds[0], 'santri_id' => $santriIds[0], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['orangtua_id' => $ortuIds[0], 'santri_id' => $santriIds[1], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['orangtua_id' => $ortuIds[1], 'santri_id' => $santriIds[2], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);

        // Insert Target Hafalan
        Capsule::table('target_hafalan')->insert([
            ['santri_id' => $santriIds[0], 'target_ayat' => 'Juz 30', 'deadline' => '2025-12-31', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['santri_id' => $santriIds[1], 'target_ayat' => 'Juz 29-30', 'deadline' => '2025-10-01', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // Insert Setoran Hafalan contoh
        Capsule::table('setoran_hafalan')->insert([
            [
                'santri_id' => $santriIds[0],
                'surat' => 'An-Naba',
                'ayat_mulai' => 1,
                'ayat_selesai' => 10,
                'jumlah_ayat' => 10,
                'nilai_quality' => 'A',
                'catatan' => 'Lancar sekali',
                'tgl_setor' => '2025-05-20',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'santri_id' => $santriIds[0],
                'surat' => 'An-Nazi\'at',
                'ayat_mulai' => 1,
                'ayat_selesai' => 5,
                'jumlah_ayat' => 5,
                'nilai_quality' => 'B',
                'catatan' => 'Murajaah perlu ditingkatkan',
                'tgl_setor' => '2025-05-25',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        echo "✅ Seeder selesai. Data berhasil dimasukkan.\n";
    }
}