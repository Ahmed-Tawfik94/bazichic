<?php


use Database\Seeds\Seed;

class DocumentsTypesSeeder extends Seed
{
    protected $tableName = 'document_types';  // Define the table name here

    public function run(): void
{
    // Now $this->old_db is available for use to interact with the 'users' table
    $data = $this->old_db->get();

    // Perform operations on $users or other logic
    $parsedData = $data->map(function ($row){
                return [
                    'title'=>$row->title,
                    'url_name'=>$row->url_name,
                    'is_published'=>$row->is_published,
                    'date_created'=>date('Y-m-d H:i:s'),
                    'date_updated'=>date('Y-m-d H:i:s'),
                ];
        })->filter()
            ->unique() // Keep only unique emails
            ->values() // Reindex the collection
            ->toArray(); // Use `filter()` to remove null rows
        // Perform operations on $users or other logic
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->table($this->tableName)->insert($parsedData)->saveData();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

}
}
