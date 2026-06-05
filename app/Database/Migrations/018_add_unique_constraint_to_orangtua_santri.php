Capsule::schema()->table('orangtua_santri', function (Blueprint $table) {
    $table->unique(['orangtua_id', 'santri_id']);
});