<?php


use Database\Seeds\Seed;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class MembershipPlansSeeder extends Seed
{
    protected $tableName = 'membersip_plans';  // Define the table name here

    public function run(): void
{
    // Now $this->old_db is available for use to interact with the 'users' table
     Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
    $plans = $this->old_db->get();
    $parsedData = $plans->map(function ($row){

        $product = $this->createProduct($row->title, $row->membersip_desc);

        // Create a recurring price for the product
        $price =$this->createPrice($row->price *100,$row->duration === 30 ? 'month':'year',10,$product->id );
            return [
                'name' => htmlspecialchars(trim($row->title ?? '')),
                'stripe_product_id'=>$product->id,
                'stripe_price_id'=>$price->id,
                'description' => htmlspecialchars(trim($row->membersip_desc ?? '')),
                'price' => is_numeric($row->price)? $row->price : 0,
                'is_available' => is_numeric($row->is_available)? $row->is_available : 0,
                'interval' => $row->duration === 30 ? 'month':'year',
                'currency'=>'USD',
                'date_created' =>$this->dateFormater($row->date_created) ,
                'date_updated' => $this->dateFormater($row->date_created),
                ];
    })->filter()
        ->unique() // Keep only unique emails
        ->values() // Reindex the collection
        ->toArray(); // Use `filter()` to remove null rows
    // Perform operations on $users or other logic
    echo 'count:'. count($parsedData).PHP_EOL;
    $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
    $this->table('membership_plans')->insert($parsedData)->saveData();
    $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

}
private function createProduct($name , $desc){
        return  Product::create([
            'name' =>$name,
            'description' =>$desc,
        ]);
}
private function createPrice($price,$interval , $trial_period ,$prd_id )
{
    return Price::create([
        'unit_amount' => $price,
        'currency' => 'USD',
        'recurring' => ['interval' => $interval,'trial_period_days' => $trial_period],
        'product' => $prd_id,
    ]);
}
}
