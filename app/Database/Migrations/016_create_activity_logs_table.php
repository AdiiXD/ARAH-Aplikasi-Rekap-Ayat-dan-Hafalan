<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateActivityLogsTable
{
    public function up()
    {
        if (!Capsule::schema()->hasTable('activity_logs')) {
            Capsule::schema()->create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('role')->nullable();
                $table->string('action');
                $table->text('description')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
                $table->index('user_id');
                $table->index('created_at');
            });
        }
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('activity_logs');
    }
}