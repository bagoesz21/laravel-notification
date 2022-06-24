<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Bagoesz21\LaravelNotification\Config\NotifConfig;

class CreateNotificationTemplatesTable extends Migration
{
    protected $notifConfig;

    public function __construct()
    {
        $this->notifConfig = NotifConfig::make();
    }

    protected function isEnabled()
    {
        return $this->notifConfig->get('tables.notification_template.enabled', false);
    }

    /**
     * Run the migrations.
     *
     * @return bool
     */
    public function up()
    {
        if(!$this->isEnabled())return false;

        Schema::create($this->notifConfig->get('tables.notification_template.table_name') ?? 'notification_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string("name")->comment("template name");

            $table->string("title")->comment("notification title");
            $table->json("message")->nullable()->comment("notification message");

            $table->timestamps();
        });
        return true;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(!$this->isEnabled())return false;
        Schema::dropIfExists($this->notifConfig->get('tables.notification_template.table_name'));
    }
}
