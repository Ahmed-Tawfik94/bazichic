<?php


use Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
final class UpdateForeignKeyUser extends Migration
{
    public function change(): void
    {
        $table = $this->table('users');

        // Remove incorrect foreign key
        if ($table->hasForeignKey('ref_user_id')) {
            $table->dropForeignKey('ref_user_id')->update();
        }

        // Add correct foreign key reference
        $table->addForeignKey('ref_user_id', 'users', 'id', ['delete' => 'SET NULL', 'update' => 'CASCADE'])
            ->update();
    }

}
