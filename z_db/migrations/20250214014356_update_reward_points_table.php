<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateRewardPointsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('reward_points');

        // Add 'referral_code' column (moved from referrals)
        if (!$table->hasColumn('referral_code')) {
            $table->addColumn('referral_code', 'string', ['limit' => 20])->update();
        }

        // Update 'transaction_type' default to 'Referral'
        if ($table->hasColumn('transaction_type')) {
            $table->changeColumn('transaction_type', 'string', ['limit' => 20, 'default' => 'Referral'])->update();
        }

        // Update 'status' column comment
        if ($table->hasColumn('status')) {
            $table->changeColumn('status', 'integer', [
                'default' => 0,
                'comment' => '0: Pending, 1: Active, 2: Not Active'
            ])->update();
        }
    }
}
