<?php
namespace Database\Seeds;

use App\Helpers\CouponGenerator;
use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Seed\AbstractSeed;
use Dotenv\Dotenv;

abstract class Seed extends AbstractSeed
{
    protected $old_db;
    protected $tableName;

    public function __construct()
    {
        parent::__construct();
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();

        // Initialize the Capsule Manager (for Eloquent ORM)
        $sourceCapsule = new Capsule();
        $sourceCapsule->addConnection([
            'driver'=>$_ENV['OLD_DB_DRIVER'],
            'host'=> $_ENV['OLD_DB_HOST'],
            'port'=>$_ENV['OLD_DB_PORT'],
            'database'=> $_ENV['OLD_DB_NAME'],
            'username'=> $_ENV['OLD_DB_USERNAME'],
            'password'=> $_ENV['OLD_DB_PASS'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]);
        $sourceCapsule->setAsGlobal();
        $sourceCapsule->bootEloquent();

        // Ensure $this->table is defined in the child classes
        if (empty($this->tableName)) {
            throw new \Exception("Table property must be defined in the child class.");
        }

        // Initialize the old_db to interact with the database using the Capsule Manager
        $this->old_db = Capsule::table($this->tableName);
    }

    function dateFormater($date){
        return strtotime($date)? date('Y-m-d H:i:s',strtotime($date)) :date('Y-m-d H:i:s');
    }
    function gen(): string
    {
        $generator = new CouponGenerator();
        $tokenLength = 16;
        return $generator->generate($tokenLength);
    }
}