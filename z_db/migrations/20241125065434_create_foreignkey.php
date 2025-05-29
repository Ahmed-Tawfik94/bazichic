<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateForeignkey extends AbstractMigration
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

        $table =    $this->table('users');
        $table->addForeignKey('role_id', 'roles', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
        ->addForeignKey('status_id', 'status', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
        ->addForeignKey('ref_user_id', 'referrals', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->update();

        $table =    $this->table('email_verifications');
            $table->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])// Foreign key
        ->update();
            $tb=$this->table('referrals');
            $tb->addForeignKey('referrer_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])  // Foreign key to users table (referrer)
            ->addForeignKey('referred_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])  // Foreign key to users table (referred)
            // Add foreign key constraint to link reward_point_id
            ->addForeignKey('reward_point_id', 'reward_points', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])->update();

    }
}
