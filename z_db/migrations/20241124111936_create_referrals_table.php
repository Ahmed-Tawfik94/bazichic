<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateReferralsTable extends AbstractMigration
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
        $table = $this->table('referrals');
        $table->addColumn('referrer_id', 'integer', ['signed' => false])  // ID of the user who referred
            ->addColumn('referred_id', 'integer', ['signed' => false])  // ID of the referred user
            ->addColumn('referral_code', 'string', ['limit' => 20])  // Code used for referral
            ->addColumn('points_awarded', 'integer', ['default' => 0])  // Points given to the referrer
            ->addColumn('status', 'integer', ['default' => 0, 'comment' => '0: Pending, 1: Approved, 2: Rejected'])  // Referral status
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('transaction_type', 'string', ['limit' => 20, 'default' => 'Referral'])
            ->addColumn('reward_point_id', 'integer', ['null' => true, 'signed' => false])  // Link to reward_points table
            ->create();
    }
}
