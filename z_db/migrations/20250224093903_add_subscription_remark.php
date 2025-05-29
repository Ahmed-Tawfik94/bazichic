<?php


use Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
final class AddSubscriptionRemark extends Migration
{

public function up()
{
    $table = $this->table('subscriptions');
    $table->addColumn('remark', 'text', ['null' => true])
        ->update();

}
public function down()
{

}
}
