<?php


use Database\Seeds\Seed;

class DocumentsReviewsSeeder extends Seed
{
    protected $tableName = 'document_reviews';  // Define the table name here

    public function run(): void
{
    // Now $this->old_db is available for use to interact with the 'users' table
    $data = $this->old_db->get();

    // Perform operations on $users or other logic
    $parsedData = $data->map(function ($row){
                return [
                    'doc_id'=>$row->doc_id,
                    'user_id'=>$row->user_id,
                    'stars'=>$row->stars,
                    'text'=>$row->text,
                    'date_created'=>$this->dateFormater($row->date_created),
                    'date_updated'=>$this->dateFormater($row->date_updated),
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
    function dateFormater($date){
        return strtotime($date)? date('Y-m-d H:i:s',strtotime($date)) :date('Y-m-d H:i:s');
    }
}
