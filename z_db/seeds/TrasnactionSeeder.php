<?php


use Database\Seeds\Seed;

class TrasnactionSeeder extends Seed
{
    protected $tableName = 'transaction_details';  // Define the table name here

    public function run(): void
{
    // Now $this->old_db is available for use to interact with the 'users' table
    $transactions = $this->old_db->get();
    $parsedData = $transactions->map(function ($row){
            return [
                'item_code' => $row->item_code === 'Starter' ? 1:2,
                'sender_id' => $row->user_id ,
                'amount' => is_numeric($row->amount) ? $row->amount : 0,
                'currency' => htmlspecialchars(trim($row->currency_code ?? '')),
                'item' => htmlspecialchars(trim($row->item_code ?? '')),
                'status' =>  'paid',
                'txn_id' => $row->txn_id,
                'created_at' => $this->dateFormater($row->timestamp),
                'updated_at' => $this->dateFormater($row->timestamp),
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
