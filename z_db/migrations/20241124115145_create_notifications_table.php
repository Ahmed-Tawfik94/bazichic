<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateNotificationsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('notifications');
        $table->addColumn('sender_id', 'integer', ['signed' => false])
            ->addColumn('receiver_id', 'integer', ['signed' => false])
            ->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('message', 'string', ['limit' => 500])
            ->addColumn('seen', 'boolean', ['default' => 0])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('sender_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addForeignKey('receiver_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->create();

    }
}
