<?php


use Database\Seeds\Seed;
use Carbon\Carbon;
class SubscriptionsSeeder extends Seed
{
    protected $tableName = 'user_memberships';  // Define the table name here

    public function run(): void
{
    // Now $this->old_db is available for use to interact with the 'users' table
    $data = $this->old_db
        ->where('date_created', '<=', Carbon::now())  // Ensure the subscription has started
        ->where('date_expiring', '>=', Carbon::now())
        ->get();

//     Perform operations on $users or other logic
    $parsedData = $data->map(function ($row){
                return [
                    'user_id'=>$row->user_id,
                    'membership_plan_id'=>$row->plan_id,
                    'stripe_subscription_id'=>'',
                    'price_id'=>'',
                    'status'=> 'active',
                    'start_date'=>$this->dateFormater($row->date_created),
                    'end_date'=>$this->dateFormater($row->date_expiring) ,
                    'created_at'=> $this->dateFormater($row->date_created),
                    'updated_at'=>$this->dateFormater($row->date_updated),
                ];
        })->filter()
            ->unique() // Keep only unique emails
            ->values() // Reindex the collection
            ->toArray(); // Use `filter()` to remove null rows
        // Perform operations on $users or other logic
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->table('subscriptions')->insert($parsedData)->saveData();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

}
}
