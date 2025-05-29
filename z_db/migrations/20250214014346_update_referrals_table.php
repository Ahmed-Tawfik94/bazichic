<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateReferralsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('referrals');

        // Remove 'transaction_type' column
        if ($table->hasColumn('transaction_type')) {
            $table->removeColumn('transaction_type')->update();
        }

        // Add foreign key to reward_points (reward_point_id -> id)
        if (!$table->hasColumn('reward_point_id')) {
            $table->addColumn('reward_point_id', 'integer', ['null' => true, 'signed' => false])
                ->addForeignKey('reward_point_id', 'reward_points', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
                ->update();
        }
    }
}
