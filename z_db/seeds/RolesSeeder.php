<?php


use Database\Seeds\Seed;

class RolesSeeder extends Seed
{
    protected $tableName = 'roles';  // Define the table name here

    public function run(): void
{
    $data = [
        [
            'name'    => 'admin',
            'description' =>'this is admin',
        ],[
            'name'    => 'moderator',
            'description' =>'this is moderator',
        ],[
            'name'    => 'user',
            'description' =>'this is customer',
        ],
    ];

    $posts = $this->table('roles');
    $posts->insert($data)
        ->saveData();


}
}
