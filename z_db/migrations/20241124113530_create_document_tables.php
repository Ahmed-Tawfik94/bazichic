<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDocumentTables extends AbstractMigration
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
// Create document_types table
        $table = $this->table('document_types');
        $table->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('url_name', 'string', ['limit' => 500])
            ->addColumn('is_published', 'boolean', ['default' => true])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();

        // Create documents table
        $table = $this->table('documents');
        $table->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('link', 'string', ['limit' => 200])
            ->addColumn('cover', 'string', ['limit' => 300])
            ->addColumn('is_downloadable', 'boolean', ['default' => false])
            ->addColumn('description', 'text')
            ->addColumn('documentType', 'integer', ['signed' => false])  // Foreign key to document_types
            ->addColumn('category_id', 'integer', ['signed' => false])  // Assuming there is a category table
            ->addColumn('user_id', 'integer')  // User who uploaded the document
            ->addColumn('author_name', 'string', ['limit' => 50])
            ->addColumn('author_link', 'string', ['limit' => 500, 'default' => ''])
            ->addColumn('author_desc', 'string', ['limit' => 1000])
            ->addColumn('num_pages', 'integer')
            ->addColumn('price', 'integer')
            ->addColumn('listen_time', 'integer', ['default' => 0])
            ->addColumn('read_time', 'integer', ['default' => 0])
            ->addColumn('tag', 'string', ['limit' => 30])
            ->addColumn('is_published', 'boolean')
            ->addColumn('file_type', 'string', ['limit' => 50, 'default' => 'Pdf'])
            ->addColumn('note', 'text')
            ->addColumn('qcode', 'string', ['limit' => 30])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('documentType', 'document_types', 'id', ['delete' => 'SET NULL', 'update' => 'CASCADE'])
            ->create();

        // Create document_audios table
        $table = $this->table('document_audios');
        $table->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('document_id', 'integer', ['signed' => false])  // Foreign key to documents
            ->addColumn('file', 'string', ['limit' => 500])
            ->addColumn('sno', 'integer', ['default' => 1])
            ->addColumn('description', 'string', ['limit' => 200])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('document_id', 'documents', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();

        // Create document_likes table
        $table = $this->table('document_likes');
        $table->addColumn('doc_id', 'integer', ['signed' => false])  // Foreign key to documents
        ->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('doc_id', 'documents', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();

        // Create document_reviews table
        $table = $this->table('document_reviews');
        $table->addColumn('doc_id', 'integer', ['signed' => false])  // Foreign key to documents
        ->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('stars', 'string', ['limit' => 10])
            ->addColumn('text', 'text')
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('doc_id', 'documents', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();

        // Create document_saves table
        $table = $this->table('document_saves');
        $table->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('doc_id', 'integer', ['signed' => false])  // Foreign key to documents
            ->addColumn('page', 'integer')
            ->addColumn('progress', 'integer')
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('doc_id', 'documents', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();

        // Create document_views table
        $table = $this->table('document_views');
        $table->addColumn('document_id', 'integer', ['signed' => false])  // Foreign key to documents
        ->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('document_id', 'documents', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();

        // Create doc_keywords table
        $table = $this->table('doc_keywords');
        $table->addColumn('doc_id', 'integer', ['signed' => false])  // Foreign key to documents
        ->addColumn('keyword', 'string', ['limit' => 100])
            ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('doc_id', 'documents', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
