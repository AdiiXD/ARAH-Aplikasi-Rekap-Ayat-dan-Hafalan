<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class AddProfilePictureToUsersTable
{
    public function up()
    {
        if (!Capsule::schema()->hasColumn('users', 'profile_picture')) {
            Capsule::schema()->table('users', function (Blueprint $table) {
                $table->string('profile_picture')->nullable()->after('password');
            });
            echo "✅ Kolom 'profile_picture' ditambahkan ke tabel users.\n";
        } else {
            echo "⚠️ Kolom 'profile_picture' sudah ada.\n";
        }
    }

    public function down()
    {
        if (Capsule::schema()->hasColumn('users', 'profile_picture')) {
            Capsule::schema()->table('users', function (Blueprint $table) {
                $table->dropColumn('profile_picture');
            });
            echo "✅ Kolom 'profile_picture' dihapus.\n";
        }
    }
}