<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateInvoiceTable extends AbstractMigration
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
    //invoice status draft, open, paid, uncollectible, or void
    public function change(): void
    {
        $table = $this->table('invoice');
        $table->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('invoice_id', 'string')
            ->addColumn('subscription_id','string', ['null' => false])
            ->addColumn('amount_paid','double')
            ->addColumn('status','enum',['values' => ['paid', 'unpaid','draft','open','uncollectible','void'], 'default' => 'unpaid'])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('paid_at' ,'timestamp', ['null' => true])
            ->addIndex('invoice_id', ['unique' => true])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
//            ->addForeignKey('subscription_id', 'subscriptions', 'stripe_subscription_id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();

    }
}
