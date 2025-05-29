<?php


use Database\Seeds\Seed;

class CategoriesSeeder extends Seed
{
    protected $tableName = 'categories';  // Define the table name here

    public function run(): void
{
    // Now $this->old_db is available for use to interact with the 'users' table
    $data = $this->old_db->get();

    // Perform operations on $users or other logic
    $parsedData = $data->map(function ($row){
                return [
                    'title'=>$row->title,
                    'description'=>$row->description,
                    'is_published'=>$row->is_published,
                    'qcode'=>$row->qcode,
                    'magazine_only'=>$row->magazine_only,
                    'taxonomy'=>$row->taxonomy,
                    'date_created'=>$this->dateFormater($row->date_created),
                    'date_updated'=>$this->dateFormater($row->date_created),
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
