<?php

namespace App\Controllers;
use App\Helpers\Constants;
use App\Helpers\Helpers;
use App\Helpers\PassHash;
use App\Models\Role;
use App\Models\Subscriptions;
use App\Models\User;
use App\Models\Util;
use App\Service\FileUpload\FileUploader;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends BaseController
{

    public function index(Request $request, Response $response, $args)
    {
        //VALIDTE SESSION
        $selected_user = $_SESSION["userID"];
        $thisUser = User::find($selected_user);
        //echo $thisUser;
        $isMember = Subscriptions::isIDExists($selected_user);
        $first_name = $thisUser["first_name"];
        //$doesHaveResume = $resumeCRUD->doesHaveResume($_SESSION["userID"]);
        $title = "My Profile";
        $admin_mode = $thisUser["role_id"]=== 1 ? 1:0;
        $thisUser['type'] = $thisUser->role->name;
        $userTypes= Role::all();
        $countries = (new Helpers())->getCountries();
        $vars = [
            'countries' => $countries,
            'page' => [
                'name'=>'profile',
                'admin_mode' => $admin_mode,
                'title' => $title,
                'description' => 'Manage Profile',
                'uname' => $first_name,
                'uid' => $selected_user,
                'thisUser' => $thisUser,
                'isMember' => $isMember,
                'userTypes' => $userTypes
            ]
        ];
        return $this->view->render($response, 'my-profile.twig', $vars);
    }

    public function resetPassword(Request $request, Response $response, $args)
    {
        $email = $request->getParam('email');
        if (!User::where('email', $email)->exists()) {
            return $this->jsonResponse($response, ['error' => true, 'message' => 'This email is not registered. Please check and try again.'], 400);
        }
        $thisUser = User::getByEmail($email);
        if (!$thisUser) {
            return $this->jsonResponse($response, ['error' => true, 'message' => 'If you are registered with this email you will shortly receive further instructions.'], 400);
        }
        $user_id = $thisUser->id;
        $password = Util::createNewUsername(8);
        $name = $thisUser->first_name . ' ' . $thisUser->last_name;

        $password_hash = password_hash($password,PASSWORD_BCRYPT);
        $res = User::updatePassword($user_id, $password_hash);
        if (!$res) {
            return $this->jsonResponse($response, ["error" => true, "message" => "Oops! An error occurred updating Password. Try again."], 400);
        }
        $emailResult = $this->reset_password_email($name, $password, $email);
        if ($emailResult["error"]) {
            return $this->jsonResponse($response, $emailResult, 400);
        }
        return $this->jsonResponse($response, ["error" => false, "message" => "We have reset the password for your account. Please check your email for further instuctions. Also check your SPAM folder before you reset again."], 200);
    }
    public function update(Request $request, Response $response, $args)
    {
        $response = $response->withHeader('Content-Type', 'application/json');
        $params=$request->getParsedBody();
        $output = array();
        $output["error"] = true;
        $id = $params['user_id'];
        $first_name = $params['first_name'];
        $last_name = $params['last_name'];
        $email = $params['email'];
        $description = $params['description'];
        $country = $params['country'];
        $dob = $params['dob'];
        $Validation =$this->validateInput([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'dob' => $dob,
            'country' => $country,
        ]);
        if ($Validation){
            return $response->withStatus(400)->getBody()->write(json_encode($Validation));
        }
        $admin_mode =(int)$_SESSION['role_id'] === 1 ? 1 :0;
        /********* START PROFILE PIC UPLOAD **********/
        $files = $request->getUploadedFiles();
        $maxFileSize = 500000;
        $res = FileUploader::uploadFile($files, 'profile_image', Constants::USER_FOLDER, Constants::IMAGES_EXT, $maxFileSize);
        if ($res['code'] == Constants::INSERT_FAILURE) {
            return $this->jsonResponse($response, $res, 400);
        }
        try {
            if ($res['code'] !== Constants::UPLOAD_IS_MISSING) {
                $result =User::updateImage($id, $res['fileName']);
                if ($_SESSION['userID'] == $id){
                $_SESSION['user_image'] = $res['fileName'];
                }
            if ($result["code"] == Constants::INSERT_FAILURE) {
                return $this->jsonResponse($response, ['error' => true,
                    'message' => "Failed to upload banner. Please try again." . $result["message"], 'id' => 1], 400);
            }
            }
            $res = User::edit($id,(object)[
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'email'=>$email,
                'dob'=>$dob,
                'country'=>$country,
                'description'=>$description
            ]);
            if($res['code'] === Constants::INSERT_FAILURE){
                return $this->jsonResponse($response, ['error' => true, 'message' => "Failed to update profile. Please try again." . $res["message"]], 400);
            }
                $message = $id === $_SESSION['userID'] ? "Your profile has been updated successfully." : $first_name . "'s profile has been updated successfully.";;
            return $this->jsonResponse($response, ['error' => false, 'message' => $message], 200);
        } catch (Exception $e) {
            return $this->jsonResponse($response, ['error' => true, 'message' => $e->getMessage()], 400);
        }
    }
    public function credUpdate(Request $request, Response $response)
    {
        $user_id = $request->getParam('user_id');
        $password = $request->getParam('pass1');
        $password2 = $request->getParam('pass2');
        $old_password = $request->getParam('old_password');
        // Validate new password match
        if ($password !== $password2) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => 'Your new password did not match.'
            ],400);
        }
        $email = User::getEmail($user_id);
        // Validate old password
        if (!User::checkLogin($email, $old_password)) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => 'Your current password did not match. Please try again.'
            ],400);
        }
        // Hash the new password
        $password_hash = password_hash($password,PASSWORD_BCRYPT);
        // Update password in database
        if (User::updatePassword($user_id, $password_hash)) {
            return $this->jsonResponse($response, [
                'error' => false,
                'message' => 'Your password has been updated successfully.'
            ]);
        }
        // Handle update failure
        return $this->jsonResponse($response, [
            'error' => true,
            'message' => 'Oops! An error occurred updating Password. Try again.'
        ],400);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $helper = new Helpers();
        $id = $request->getParam('user_id');

        /******** VERIFY THE REQUESTING AGENT **********/
        $agentValidator = $helper->getRequestingAgent($request, $this->db);
        if ($agentValidator["error"]) {
            return $this->jsonResponse($response, ["error" => true, "message" => "We could not authenticate this request.", 'agentValidationError' => $agentValidator], 400);
        }
        $agentID = $agentValidator["user_info"]["id"];
        $agentRole = $agentValidator["user_info"]["role_id"];
        /******** VERIFY IF NON ADMIN  **********/
        if ($agentRole != 1) {
            return $this->jsonResponse($response, ['error' => true, 'message' => 'You are not authorized to perform this action.'], 400);
        }
        $res = User::remove($id);
        if (!$res) {
            return $this->jsonResponse($response, ["error" => true, "message" => "Failed to delete user. Please try again."], 400);
        }

        return $this->jsonResponse($response, ["error" => false, "message" => "User profile has been deleted successfully. ", "id" => $id]);

    }
    public function create(Request $request, Response $response, $args)
    {
        $roles= Role::all();
        $countries = (new Helpers())->getCountries();
        $vars = [
            'countries' => $countries,
            'page' => [
                'moderator' => true,
                'title' => 'Create New Account',
                'description' => 'Add a new user account on BaziChic',
                "roles"=>$roles,
                'name'=>'manage-users'
            ]
        ];
        return $this->view->render($response, 'admin/add-new-user.twig', $vars);
    }
    public function passwordRecovery(Request $request, Response $response, $args)
    {
        $vars = [
            'page' => [
                'moderator' => true,
                'title' => 'Recover Password',
                'description' => 'Recover your lost password at BaziChic'
            ]
        ];
        return $this->view->render($response, 'recover-password.twig', $vars);
    }

    public function PreviewUser(Request $request, Response $response, $args)
    {
        $response = $response->withHeader('Content-Type', 'application/json');
        $output = "";
        $id = $request->getAttribute('id');
        $thisUser = User::getID($id);
        if ($thisUser != null) {
            $userName = $thisUser["name"];
            $output = $userName . "";
        } else {
            $output = "Failed to load user preview.";
            $response->getBody()->write($output);
        }
        return $response;
    }
    private function validateInput($data): ?array
    {
        $requiredFields = [
            'first_name' => 'First name cannot be empty.',
            'last_name' => 'Last name cannot be empty.',
            'email' => 'Let us know your email.',
            'dob' => 'Let us know your birth date.',
            'country' => 'Select your country of residence.',
        ];

        foreach ($requiredFields as $field => $errorMessage) {
            if (empty($data[$field])) {
                return  [
                "error" => true,
                    "message" => $errorMessage,
                ];
            }
        }

        return null; // No validation errors
    }

    /**
     * @param $name
     * @param string $password
     * @param $email
     * @return array
     */
    public function reset_password_email($name, string $password, $email): array
    {
        $helper = new Helpers();
        $twig = $this->TwigTemplate();
        $base_url = $_ENV["APP_URL"];
        $template = $twig->render('reset-password-template.twig', ['name' => $name, 'password' => $password, 'base_url' => $base_url]);
        $subject = 'Bazichic Account Password Recovery';
        $emailResult = $helper->sendEmail($email, $subject, $template);
        if ($emailResult["status"] === 'error') {
            return ["error" => true, "message" => "Oops! An error occurred sending your email. Please try again."];
        }
        return ["error" => false, "message" => " We have sent further instructions to your registered email address. Please check your email in a moment."];

    }
}
