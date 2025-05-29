<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUserSubscriptionsTable extends AbstractMigration
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
        $table = $this->table('subscriptions');
        $table->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('membership_plan_id', 'integer', ['signed' => false])  // Add foreign key reference for membership plans
            ->addColumn('stripe_subscription_id', 'string', ['null' => true])
            ->addColumn('price_id', 'string', ['null' => true])
            ->addColumn('status', 'enum', ['values' => ['active', 'trialing', 'canceled', 'past_due', 'unpaid','incomplete_expired'], 'default' => 'trialing'])
            ->addColumn('trial_end_date', 'date', ['null' => true])
            ->addColumn('start_date', 'date', ['null' => true])
            ->addColumn('end_date', 'date', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])  // Existing foreign key for users
            ->addForeignKey('membership_plan_id', 'membership_plans', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])  // New foreign key for membership plans
            ->create();

    }
}
