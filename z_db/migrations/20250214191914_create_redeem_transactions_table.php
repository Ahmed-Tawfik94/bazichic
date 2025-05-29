<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateRedeemTransactionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('redeem_transactions');
        $table->addColumn('user_id', 'integer', ['signed' => false]) // Redeemer's User ID
            ->addColumn('points', 'integer', ['signed' => false]) // Points Redeemed
            ->addColumn('type', 'string', ['limit' => 20]) // Type: redeem_coupon or withdraw_money
            ->addColumn('status', 'integer', [
                'default' => 0,
                'comment' => '0: Pending, 1: Approved, 2: Rejected'
            ]) // Redemption Status
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']) // Timestamp
            // Add foreign key to users table
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
