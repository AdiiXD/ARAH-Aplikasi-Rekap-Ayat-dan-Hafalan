<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable
{
    public function up()
    {
        if (!Capsule::schema()->hasTable('settings')) {
            Capsule::schema()->create('settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('key');
                $table->text('value')->nullable();
                $table->enum('type', ['text', 'boolean', 'json'])->default('text');
                $table->timestamps();
                
                $table->index('user_id');
                $table->unique(['user_id', 'key']);
            });
        }
        
        // Insert default settings untuk admin (global)
        $this->seedDefaultSettings();
    }

    private function seedDefaultSettings()
    {
        $defaults = [
            ['app_name', 'Hafalan Tracker', 'text'],
            ['app_timezone', 'Asia/Jakarta', 'text'],
            ['date_format', 'd-m-Y', 'text'],
            ['notif_email_enabled', '1', 'boolean'],
            ['reminder_days_before', '3', 'text'],
            ['default_qari', 'ar.alafasy', 'text'],
            ['default_tajwid', '0', 'boolean'],
            ['default_translation', '1', 'boolean'],
        ];
        
        foreach ($defaults as $def) {
            $exists = Capsule::table('settings')
                ->whereNull('user_id')
                ->where('key', $def[0])
                ->exists();
            if (!$exists) {
                Capsule::table('settings')->insert([
                    'user_id' => null,
                    'key' => $def[0],
                    'value' => $def[1],
                    'type' => $def[2],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('settings');
    }
}