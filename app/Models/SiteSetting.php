<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Optional if you want to use soft deletes
use App\Helpers\Constants;

class SiteSetting extends Model
{
    use HasFactory; // Enable model factories

    // Optional: Use this if you want soft deletes
    // use SoftDeletes;

    protected $table = 'site_settings'; // Specify the table name if it differs from the default 'site_settings'

    protected $fillable = [
        'name',
        'admin_email',
        'maintenance_on',
        'banner_link',
        'site_email',
        'phone'
    ];

    // Optional: If your table has created_at and updated_at columns, set this to true
    public $timestamps = false; // Change to true if you want Eloquent to manage timestamps

    // Method to update site settings
    public  function updateSettings(object $data, $id): array
    {
        try {
            $record = $this->updateOrCreate (['id'=>$id],
                [
                    'name'=>$data->name,
                    'admin_email'=>$data->admin_email,
                    'maintenance_on'=>$data->maintenance_on,
                    'site_email'=>$data->site_email,
                    'phone'=>$data->phone,
                    ]);
            return ['error' => false,'data'=>$record,'code' => Constants::INSERT_SUCCESS];
        } catch (\Exception $e) {
            return ['error' => true, 'code' => Constants::INSERT_FAILURE, 'message' => $e->getMessage()];
        }
    }

    // Method to update the banner link
    public  function updateBannerLink($id,$bannerLink): array
    {
        try {
           $record = $this->updateOrCreate (['id'=>$id],
                ['banner_link' => $bannerLink]);
            return ['error' => false,'data'=>$record,'code' => Constants::INSERT_SUCCESS];
        } catch (\Exception $e) {
            return ['error' => true, 'code' => Constants::INSERT_FAILURE, 'message' => $e->getMessage()];
        }
    }

    // Get a specific setting by ID
    public  function getID($id)
    {
        return $this->find($id);
    }

    // Check if maintenance mode is on
    public  function isMaintenanceModeOn()
    {
        return $this->where('id', 1)->value('maintenance_on');
    }

    public function getFrontBannerLink()
    {

        return $this->where('id', 1)->value('banner_link');
    }
}
