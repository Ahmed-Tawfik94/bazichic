<?php

use Database\Seeds\Seed;

class MigratingUserTable extends Seed
{
    protected $tableName='users';
    public function run(): void
    {

        // Fetch data using Eloquent
        $sourceData = $this->old_db->get()->unique('email');
        $parsedData = $sourceData->map(function ($row){
            $email = filter_var($row->email, FILTER_SANITIZE_EMAIL);
            if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return [
                    'id'=>$row->id,
                    'first_name' => htmlspecialchars(trim($row->first_name ?? '')),
                    'last_name' => htmlspecialchars(trim($row->last_name ?? '')),
                    'email' => $email,
                    'phone' => htmlspecialchars(trim($row->phone ?? '')),
                    'description' => htmlspecialchars(trim($row->description ?? '')),
                    'password' => $row->password,
                    'user_image' => $row->user_image ?? null,
                    'user_name' => htmlspecialchars(trim($row->user_name ?? '')),
                    'referral_code' => htmlspecialchars(trim($row->referral_code ?? '')),
                    'country'=>$row->country,
                    'status_id' => 1,
                    'role_id'=> $row->role_id !==1 ? 3 : 1,
                    'api_key' => $row->api_key ?? null,
                    'created_at' =>$this->dateFormater($row->date_created) ,
                    'updated_at' => $this->dateFormater($row->date_updated),
                    'last_active' => $this->dateFormater($row->last_active),
                    'dob' => isset($row->dob) ? date('Y-m-d', strtotime($row->dob)) : null,
                    'reg_source' => htmlspecialchars(trim($row->reg_source ?? 'unknown')),
                ];
            }
            return null;
        })->filter()
            ->unique() // Keep only unique emails
            ->values() // Reindex the collection
            ->toArray(); // Use `filter()` to remove null rows

        echo 'Inserting data to table'. count($parsedData).PHP_EOL;
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->table('users')->insert($parsedData)->saveData();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
        echo 'Finished inserting'.PHP_EOL;
    }
}
