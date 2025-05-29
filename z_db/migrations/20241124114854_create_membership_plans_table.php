<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMembershipPlansTable extends AbstractMigration
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
        $table = $this->table('membership_plans');
        $table->addColumn('name', 'string', ['limit' => 200])
            ->addColumn('stripe_product_id', 'string', ['limit' => 100])
            ->addColumn('stripe_price_id', 'string', ['limit' => 100])
            ->addColumn('description', 'string', ['limit' => 255])
            ->addColumn('price', 'double', ['default' => 0])
            ->addColumn('is_available', 'integer', ['default' => 0])
            ->addColumn('currency', 'string', ['limit' => 30])
            ->addColumn('interval', 'string', ['limit' => 200])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
