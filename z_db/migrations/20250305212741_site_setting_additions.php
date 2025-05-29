<?php


use Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
final class SiteSettingAdditions extends Migration
{

public function up()
{
    $table = $this->table('site_settings');
    $table->addColumn('phone', 'string', ['limit' => 255])
        ->addColumn('site_email', 'string', ['limit' => 255])
        ->update();

}
public function down()
{

}
}
