<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateRewardPointsTable extends AbstractMigration
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
        $table = $this->table('reward_points');
        $table->addColumn('user_id', 'integer', ['signed' => false]) // Reference to the user
            ->addColumn('points', 'integer') // Reward points value
            ->addColumn('transaction_type', 'string', ['limit' => 20, 'default' => 'None']) // Type of transaction
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']) // Creation timestamp
            ->addColumn('status', 'integer', [
                'default' => 0,
                'comment' => '0: Pending, 1: Approved, 2: Rejected'
            ]) // Reward status
            // Add foreign key constraint for user_id referencing the users table
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
