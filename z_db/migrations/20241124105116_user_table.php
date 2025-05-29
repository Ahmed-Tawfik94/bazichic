<?php

declare(strict_types=1);
use Phinx\Migration\AbstractMigration;

final class UserTable extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('first_name', 'string', ['limit' => 100])
            ->addColumn('last_name', 'string', ['limit' => 100])
            ->addColumn('stripe_customer_id', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('email', 'string', ['limit' => 100 ,'null'=>false])
            ->addColumn('phone', 'string', ['limit' => 20, 'default' => ''])
            ->addColumn('country', 'string', ['limit' => 30, 'default' => ''])
            ->addColumn('description', 'string', ['limit' => 200, 'default' => ''])
            ->addColumn('password', 'string', ['limit' => 200])
            ->addColumn('user_image', 'string', ['limit' => 300, 'default' => ''])
            ->addColumn('user_name', 'string', ['limit' => 30, 'null' => false])
            ->addColumn('referral_code', 'string', ['limit' => 20, 'null' => true])
            ->addColumn('api_key', 'string', ['limit' => 50])
            ->addColumn('status_id', 'integer', ['default' => 1,'signed' => false])
            ->addColumn('ref_user_id', 'integer', ['null' => true,'signed' => false])  // Optional reference to the referring user
            ->addColumn('role_id', 'integer',['default' => 3,'signed' => false])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP'
            ])
            ->addColumn('last_active', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('dob', 'string', ['limit' => 30, 'default' => ''])
            ->addColumn('reg_source', 'string', ['limit' => 10, 'default' => ''])
            ->addIndex(['email'], ['unique' => true]) // Ensure unique emails
            ->addIndex(['api_key'], ['unique' => true]) // Ensure unique api_key
            ->addIndex(['user_name'], ['unique' => true]) // Optional: unique usernames
            ->addIndex(['stripe_customer_id'], ['unique' => true]) // Optional: unique stripe_customer_id
            ->create();
    }
}
