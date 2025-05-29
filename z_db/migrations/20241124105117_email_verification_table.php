<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class EmailVerificationTable extends AbstractMigration
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
        // Create the email_verifications table
        $table = $this->table('email_verifications');

        $table->addColumn('user_id', 'integer',['signed' => false]) // Foreign key reference to users table
        ->addColumn('token', 'string', ['limit' => 255]) // Unique verification token
        ->addColumn('expires_at', 'timestamp', ['null' => true]) // Expiration timestamp
        ->addColumn('verified_at', 'timestamp', ['null' => true]) // Timestamp for when verification occurs
        ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']) // Creation timestamp
        ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP']) // Updated timestamp
        ->addIndex(['token'], ['unique' => true]) // Ensure token is unique
        ->create();
    }
}
