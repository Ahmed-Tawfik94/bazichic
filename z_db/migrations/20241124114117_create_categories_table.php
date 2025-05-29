<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCategoriesTable extends AbstractMigration
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
        $table = $this->table('categories');
        $table->addColumn('title', 'string', ['limit' => 50])
            ->addColumn('description', 'string', ['limit' => 300, 'default' => ''])
            ->addColumn('is_published', 'boolean', ['default' => 1])
            ->addColumn('qcode', 'string', ['limit' => 50, 'default' => ''])
            ->addColumn('magazine_only', 'boolean', ['default' => 0])
            ->addColumn('taxonomy', 'string', ['limit' => 50])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
