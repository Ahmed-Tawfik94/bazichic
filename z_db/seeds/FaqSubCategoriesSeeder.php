<?php


use Database\Seeds\Seed;
use App\Helpers\CouponGenerator;
class FaqSubCategoriesSeeder extends Seed
{
    protected $tableName = 'faq_sub_categories';  // Define the table name here

    public function run(): void
{
    // Now $this->old_db is available for use to interact with the 'users' table
    $data = $this->old_db->get();

    // Perform operations on $users or other logic
    $parsedData = $data->map(function ($row){
                return [
                    'title'=>$row->title,
                    'qcode'=>$this->gen(),
                    'category_id'=>$row->category_id,
                    'date_created'=>$this->dateFormater($row->timestamp),
                    'date_updated'=>$this->dateFormater($row->timestamp),
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
