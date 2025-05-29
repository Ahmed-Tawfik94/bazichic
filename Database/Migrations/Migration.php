<?php
namespace Database\Migrations;
use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Builder as SchemaBuilder;
use RuntimeException;

abstract class Migration extends AbstractMigration
{
    /**
     * @var SchemaBuilder
     */
    protected $schema;
    protected $db;

    public function __invoke ()
    {

        // Use the globally set Capsule instance
        if (!Capsule::connection()) {
            throw new RuntimeException('Capsule is not properly initialized.');
        }
        $this->schema = Capsule::schema();
    }

    /**
     * Access the Schema Builder
     *
     * @return SchemaBuilder
     */
    protected function schema(): SchemaBuilder
    {
        return $this->schema;
    }
}
