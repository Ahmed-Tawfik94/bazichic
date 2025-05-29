<?php


use Database\Seeds\Seed;

class DocumentsSeeder extends Seed
{
    protected $tableName = 'documents';  // Define the table name here

    public function run(): void
{
    // Now $this->old_db is available for use to interact with the 'users' table
    $data = $this->old_db->get();

    // Perform operations on $users or other logic
    $parsedData = $data->map(function ($row){
                return [
                    'title'=>$row->title,
                    'link'=>'uploads/documents/'.$row->link,
                    'cover'=>'uploads/images/docs/'.$row->cover,
                    'is_downloadable'=>$row->is_downloadable,
                    'description'=>$row->description,
                    'documentType'=>$row->document_type,
                    'category_id'=>$row->category_id,
                    'user_id'=>$row->user_id,
                    'author_name'=>$row->author_name,
                    'author_link'=>$row->author_link,
                    'author_desc'=>$row->author_desc,
                    'num_pages'=>$row->num_pages,
                    'price'=>$row->price,
                    'listen_time'=>$row->listen_time,
                    'read_time'=>$row->read_time,
                    'tag'=>$row->tag,
                    'is_published'=>$row->is_published,
                    'file_type'=>$row->file_type,
                    'note'=>$row->note,
                    'qcode'=>$row->qcode,
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
}
