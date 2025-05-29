<?php


use Database\Seeds\Seed;

class StatusSeeder extends Seed
{
    protected $tableName = 'status';  // Define the table name here

    public function run(): void
{
    $data = [
        [
            'name'    => 'verified',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],[
            'name'    => 'unverified',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],[
            'name'    => 'completed',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],[
            'name'    => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
    ];

    $posts = $this->table('status');
    $posts->insert($data)
        ->saveData();

}
}
