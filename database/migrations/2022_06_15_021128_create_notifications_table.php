<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Bagoesz21\LaravelNotification\Enums\NotificationLevel;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');

            $table->string('title')->nullable();
            $table->json('message')->nullable();

            $enums = collect(NotificationLevel::getAllAsArray())
                ->map(fn($enum) => "{$enum['value']} = {$enum['description']}")
                ->join(', ');
            $table->tinyInteger('level')->nullable()->default(NotificationLevel::getDefaultValue())->comment($enums);

            $table->string('image')->nullable();

            $table->text('data');
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}