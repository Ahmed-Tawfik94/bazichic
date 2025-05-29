<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAppUsageTable extends AbstractMigration
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
        $table = $this->table('app_usage');
        $table->addColumn('api_key', 'string', ['limit' => 40])
            ->addColumn('ipAddress', 'string', ['limit' => 50, 'default' => ''])
            ->addColumn('signature', 'string', ['limit' => 200, 'default' => ''])
            ->addColumn('callerInfo', 'string', ['limit' => 100, 'default' => ''])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
