<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFaqTables extends AbstractMigration
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
        // Create faq_categories table
        $table = $this->table('faq_categories');
        $table->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['title'], ['unique' => true])  // Optional: Add an index for fast lookups
            ->create();

        // Create faq_sub_categories table
        $table = $this->table('faq_sub_categories');
        $table->addColumn('title', 'string', ['limit' => 200])
            ->addColumn('qcode', 'string', ['limit' => 100])
            ->addColumn('category_id', 'integer', ['signed' => false])  // Foreign key to faq_categories
//            ->addColumn('sort_id', 'integer', ['default' => 1])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('category_id', 'faq_categories', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addIndex(['category_id'])
            ->addIndex(['qcode'],['unique' => true])
            ->create();

        // Create faqs table
        $table = $this->table('faqs');
        $table->addColumn('title', 'string', ['limit' => 200])
            ->addColumn('category_id', 'integer', ['signed' => false])  // Foreign key to faq_categories
            ->addColumn('subcategory_id', 'integer', ['default' => 0, 'signed' => false])  // Foreign key to faq_sub_categories (optional)
            ->addColumn('description', 'text')
            ->addColumn('url', 'string', ['limit' => 100])
            ->addColumn('is_published', 'boolean', ['default' => true])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('category_id', 'faq_categories', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('subcategory_id', 'faq_sub_categories', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();

    }
}
