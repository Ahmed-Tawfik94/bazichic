<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class SiteSettingsTable extends AbstractMigration
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
        $table = $this->table('site_settings');
        $table->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('admin_email', 'string', ['limit' => 255])
            ->addColumn('maintenance_on', 'boolean', ['default' => false])
            ->addColumn('banner_link', 'string', ['limit' => 255,'default' => 'uploads/images/banners/bg.jpg'])
            ->addIndex(['name'], ['unique' => true])
            ->create();

    }
}
