<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateActivitesTable extends AbstractMigration
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
        $table = $this->table('activities');
        $table->addColumn('who_id', 'integer')
            ->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('message', 'string', ['limit' => 300])
            ->addColumn('data_id', 'string', ['limit' => 20])
            ->addColumn('data_title', 'string', ['limit' => 30])
            ->addColumn('seen', 'boolean', ['default' => 0])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
