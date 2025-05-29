<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTransactionDetailsTable extends AbstractMigration
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
        $table = $this->table('transaction_details');
        $table->addColumn('item_code', 'integer', ['default' => 3,'signed' => false])
            ->addColumn('sender_id', 'integer',['default' => 3,'signed' => false])
            ->addColumn('amount', 'double', ['default' => 0])
            ->addColumn('item', 'string', ['limit' => 255])
            ->addColumn('status', 'enum', ['values' => ['paid', 'unpaid'], 'default' => 'unpaid'])
            ->addColumn('currency', 'string', ['limit' => 255])
            ->addColumn('txn_id', 'string', ['limit' => 255])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP'
            ])
            ->addForeignKey('sender_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('item_code', 'membership_plans', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
