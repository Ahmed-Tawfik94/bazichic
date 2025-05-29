<?php

namespace App\Models;
use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\PassHash;
class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['first_name', 'last_name', 'stripe_customer_id', 'email', 'phone', 'country', 'description', 'password', 'user_image', 'user_name', 'referral_code', 'api_key', 'status_id', 'ref_user_id', 'role_id', 'last_active', 'dob', 'reg_source',];

    public static function register(object $data)
    {
        $response = array();
        $response["error"] = true;

        try {
            // Check if the email is already registered
            if (!self::isEmailRegistered($data->email)) {
                // Create a new User instance
                $user = new User();

                // Set the user attributes
                $user->first_name = $data->first_name;
                $user->last_name = $data->last_name;
                $user->email = $data->email;
                $user->phone = $data->phone;
                $user->country = $data->country;
                $user->password = password_hash($data->password,PASSWORD_BCRYPT);
                // $user->referral_code = $data->referral_code;
                $user->api_key = $data->api_key;
                $user->status_id = 2;
                $user->ref_user_id = $data->ref_user_id > 0 ? $data->ref_user_id : null;
                $user->role_id = $data->role_id ?? 3;
                $user->reg_source = $data->reg_source ||'';
                $user->dob = $data->dob;
                $user->user_name =strtolower( $data->first_name . '_' . $data->last_name.Util::createNewUsername(6));

                // Save the user instance
                if ($user->save()) {
                    $response["error"] = false;
                    $response["id"] = $user->id; // Get the last inserted ID
                    $response["code"] = Constants::INSERT_SUCCESS;
                    $response["userName"] = $user->user_name;
                    $response["message"] = "Great! You are now a registered member.";
                } else {
                    $response["error"] = true;
                    $response["message"] = "Oops! An error occurred while registering. Try again.";
                    $response["code"] = Constants::INSERT_FAILURE;
                }
            } else {
                $response["error"] = true;
                $response["message"] = "Looks like you are already registered.";
                $response["code"] = Constants::ALREADY_EXIST;
            }

            return $response;
        } catch (\Exception $e) {
            $response["error"] = true;
            $response["code"] = Constants::INSERT_FAILURE;
            $response["message"] = "An exception occurred: " . $e->getMessage();
            echo $e->getMessage();
            return $response;
        }
    }
    public static function updateUserRole($id,$role_id){
        try {
            $user = User::find($id);
            $user->role_id = $role_id;
            $user->save();
        }catch (\Exception $e){
            throw new \Error('Could not update user role');
        }
    }

    public static function isValid($referral_code)
    {
        // Check if the referral code exists in the database
        return self::where('referral_code', $referral_code)->exists();
    }

    public static function getUserID($code)
    {
        return self::where('referral_code', $code)->value('id');
    }

    public static function edit($id, $data)
    {
        $response = array();
        $response["error"] = true;

        try {
            // Create a new User instance
            $user = self::find($id);
            // Set the user attributes
            $user->first_name = $data->first_name;
            $user->last_name = $data->last_name;
            $user->email = $data->email;
            $user->dob = $data->dob;
            $user->country = $data->country;
            $user->description = $data->description;

            // Save the user instance
            if ($user->save()) {
                $response["error"] = false;
                $response["id"] = $user->id; // Get the last inserted ID
                $response["code"] = Constants::INSERT_SUCCESS;
                $response["message"] = "Great! You are now a registered member.";
            } else {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while registering. Try again.";
                $response["code"] = Constants::INSERT_FAILURE;
            }

            return $response;
        } catch (\Exception $e) {
            $response["error"] = true;
            $response["message"] = "An exception occurred: " . $e->getMessage();
            return $response;
        }
    }

    public static function remove($id)
    {
        try {
            // Find the user by ID
            $user = User::find($id);

            if ($user) {
                // Delete the user
                $user->delete();
                return true;
            }

            return false; // User not found
        } catch (\Exception $e) {
            // Handle exception if needed
            echo $e->getMessage();
            return false;
        }
    }
    public static function getNumUsers($role_id)
    {
        return self::where('role_id', $role_id)->count();
    }
    public static function getCountriesUsersSummary($startDate = null, $endDate = null)
    {
        $query = self::select('country')
        ->selectRaw('COUNT(*) as numUsers')
        ->groupBy('country');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->orderBy('numUsers', 'DESC')
        ->limit(10)
            ->get();
    }
    public static function getByUsername($user_name)
    {
        return self::where('user_name', $user_name)->first();
    }
    public static function getByEmail($email)
    {
        return self::where('email', $email)->first();
    }
    public static function isValidApiKey($api_key)
    {
        // Check if any user exists with the given api_key
        return User::where('api_key', $api_key)->exists();
    }
    public static function getUserByApiKey($api_key)
    {
        // Retrieve the user record where the api_key matches
        return User::where('api_key', $api_key)->first();
    }
    public static function getUserIdByEmail($email)
    {
        // Retrieve the user ID where the email matches
        return User::where('email', $email)->value('id');
    }
    public static function getNameByID($id)
    {
        // Retrieve the user ID where the email matches
        $user = User::where('id', $id);
        return $user->value('first_name') . ' ' . $user->value('last_name');
    }
    public static function isEmailRegistered($email)
    {
        // Retrieve the user ID where the email matches
        return User::where('email', $email)->exists();
    }
    public static function getEmail($id)
    {
        // Retrieve the user by ID
        $user = User::find($id);
        // Return the email if the user is found, otherwise return null
        return $user ? $user->email : null;
    }
    public static function getRoleID($id)
    {
        // Retrieve the user by ID
        $user = User::find($id);
        // Return the email if the user is found, otherwise return null
        return $user ? $user->role_id : null;
    }

    public static function updatePassword($user_id, ?string $password)
    {
        $email_exists = self::find($user_id);
        if ($email_exists){
           $email_exists->password =$password;
           $email_exists->save();
           return true;
        }
        return false;
    }

    public static function updateImage($id, string $uploadCoverName)
    {
        try {
            $user = self::find($id);
            if (!$user) {
                return ['success' => false, 'message' => 'User not found.'];
            }
            $user->user_image = $uploadCoverName;
            $user->save();
            return ['error' => false,'data'=>$user,'code' => Constants::INSERT_SUCCESS];
        }catch (\Exception $e){
            return ['error' => true, 'code' => Constants::INSERT_FAILURE, 'message' => $e->getMessage()];
        }

    }

    public static function getUserImageByID($id)
    {
        // Retrieve the user by ID
        $user = User::find($id);

        // Return the user_image if the user is found, otherwise return null
        return $user ? $user->user_image : null;
    }
    public static function checkLogin($email,$password){
        $email_exists = self::where('email',$email)->first();
        if ($email_exists){
            $verify = password_verify($password, $email_exists->password);
        }
        return $verify ?? false;
    }
    public static function updateLastActive($user_id,$date_created){
        $user = self::find($user_id);
        $user->last_active = date('Y-m-d H:i:s', strtotime($date_created));
        $user->save();
    }

    public static function whoReferedThis($userID)
    {
        try{
            $user= self::find($userID);
            return $user->ref_user_id;

        }catch (\Exception $e){
            print_r($e);
        }
    }

    /**
     * Returns the likes associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DocumentLike::class,'user_id');
    }
    /**
     * Returns the role associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public static  function isAdmin ($id){
        return self::find($id)->role->id === 1 ;
    }
    public function subscription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Subscriptions::class);
    }
    public function transaction (): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Payment::class);
    }
    public function notifications(){
        return $this->hasMany(Notification::class);
    }
}
