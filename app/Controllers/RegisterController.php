<?php

namespace App\Controllers;

use App\Helpers\Constants;
use App\Helpers\Helpers;
use App\Models\Activity;
use App\Models\EmailVerifications;
use App\Models\Referral;
use App\Models\RewardPoint;
use App\Models\User;
use App\Models\Util;
use App\Service\stripe\StripeService;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RegisterController extends BaseController
{

    public function index(Request $request, Response $response, $args)
    {
        if (isset($_SESSION['userID'])) {
            $url= $this->container->get('router')->pathFor('dashboard');
            return $response->withHeader('Location', $url);
        }
        $countries = (new Helpers())->getCountries();
        $queryParams = $request->getQueryParams();
        $referralCode = $queryParams['referral_code'] ?? '';

        $vars = [
            'countries' => $countries,
            'page' => [
                'moderator' => false,
                'title' => 'Register Account | BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics'
            ],
            'referral_code' => $referralCode, // Pass referral code to the view
        ];
        return $this->view->render($response, 'register.twig', $vars);
    }

    public function register(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();
        $output = array();
        $output["note"] = "";

        // Reading post parameters
        $first_name = $params['first_name'];
        $last_name = $params['last_name'];
        $email = $params['email_reg'];
        $dob = $params['dob'];
        $country = $params['country'];
        $referral_code = $params['referral_code'] ?? '';
        $ref_user_id = 0;
        $password = $params['password'];
        $confirm_password = $params['password_repeat'];

        // Validate password fields
        if (empty($password) || empty($confirm_password)) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => 'Please enter and confirm your password.'
            ], 400);
        }

        if ($confirm_password !== $password) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => 'Password and Confirm Password do not match.'
            ], 400);
        }

        if (strlen($password) < 6) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => 'Password must be at least 6 characters long.'
            ], 400);
        }

        // Validate required fields
        foreach (['first_name', 'last_name', 'email_reg', 'dob', 'country'] as $field) {
            if (empty($params[$field])) {
                return $this->jsonResponse($response, [
                    'error' => true,
                    'message' => ucfirst(str_replace('_', ' ', $field)) . ' cannot be empty 2.'
                ], 400);
            }
        }

        // Check if referral code exists in reward_points
        if (!empty($referral_code)) {
            $referrerReward = RewardPoint::where('referral_code', $referral_code)->first();

            if (!$referrerReward) {
                return $this->jsonResponse($response, [
                    'error' => true,
                    'message' => 'Invalid referral code. Please try again.'
                ], 400);
            }

            $ref_user_id = $referrerReward->user_id; // Get referrer's user ID
        }

        $phone = "";
        $date_created = date('Y-m-d H:i:s');
        $api_key = Util::generateApiKey();
        $user_name = Util::createNewUsername(8);

        // Register user
        $res = User::register((object)[
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone' => $phone,
            'email' => $email,
            'password' => $password,
            'dob' => $dob,
            'country' => $country,
            'status_id' => 3, // Pending approval
            'ref_user_id' => $ref_user_id,
            'api_key' => $api_key,
            'reg_source' => $params['reg_source'] ?? ''
        ]);

        if ($res["code"] == Constants::INSERT_SUCCESS) {
            $output["error"] = false;
            $output["message"] = "Great! Your new account has been registered successfully.";
            $user_id = $res["id"];
            $output["id"] = $user_id;
            $output["user_name"] = $user_name;

            // Create Stripe customer
            $stripe = new StripeService();
            $customer = $stripe->createCustomer((object)[
                'name' => $first_name . ' ' . $last_name,
                'email' => $email
            ]);

            if ($customer) {
                User::where('id', $user_id)->update(['stripe_customer_id' => $customer->id]);
            }

            // Record referral in `referrals` table (status = pending)
            if ($ref_user_id) {
                Referral::createReferral([
                    'referrer_id' => $ref_user_id,
                    'referred_id' => $user_id,
                    'reward_point_id' => $referrerReward->id, // Link to reward_points
                    'points_awarded' => 10, // Initial referral points
                    'status' => 0, // Pending approval
                    'date_created' => $date_created,
                ]);
            }

            /********* Send verification email ********/
            $output = $this->VerificationLink($user_id, $first_name, $email, $output);
            $title = $this->sendNotification($first_name, $user_id, $user_name, $date_created, $output["note"]);
            /********* Done ********/

            /**** Log Activity ********/
            try {
                $title = "New Registration - " . $first_name . " " . $last_name;
                $activity = $first_name . " " . $last_name . " registered an account.";
                $activity_res = Activity::createActivity((object)[
                    'who_id' => 1,
                    'title' => $title,
                    'message' => $activity,
                    'user_name' => $user_name,
                    'data_id' => $user_id,
                    'data_title' => "Registration",
                ]);
                if ($activity_res["code"] == Constants::INSERT_SUCCESS) {
                    $output["note"] .= " Logged new account activity.";
                }

            } catch (Exception $e) {
                $output["note"] .= "Error logging activity. " . $e->getMessage();
            }
            /**** Log Activity ********/

            /********** PROCESS REFERRAL ***********/
            $this->RecordActivity($ref_user_id, $referral_code, $user_id, $output, $date_created);
            /********** REFERRAL PROCESSED ***********/

            return $this->jsonResponse($response, $output);
        }

        if ($res["code"] == Constants::INSERT_FAILURE) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => $res["message"] ?? "Oops! An error occurred while registering the user."
            ], 400);
        }

        if ($res["code"] == Constants::ALREADY_EXIST) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => "Email already exists, please use a different email."
            ], 400);
        }
    }


    public function VerifyAccount(Request $request, Response $response, $args)
    {
        $params = $request->getQueryParams();

        if (!isset($params['token'])) {
            $url = $this->container->get('router')->pathFor('login', [], ['message' => 'Token not provided.', 'status' => 'error']);
            return $response->withHeader('Location', $url)->withStatus(302);
        }

        $token = $params['token'];
        $res = EmailVerifications::verify($token);

        if ($res['status'] !== 'success') {
            $url = $this->container->get('router')->pathFor('login', [], ['message' => $res['message'], 'status' => 'error']);
            return $response->withHeader('Location', $url)->withStatus(302);
        }

        $user = User::find($res['user_id']);
        if (!$user) {
            $url = $this->container->get('router')->pathFor('login', [], ['message' => 'User not found.', 'status' => 'error']);
            return $response->withHeader('Location', $url)->withStatus(302);
        }

        // Check if the user already has a referral code in reward_points
        $existingReferral = RewardPoint::where('user_id', $user->id)->where('transaction_type', 'Referral')->first();

        if (!$existingReferral) {
            // Generate a referral code and store it in `reward_points`
            $referralCode = $this->generateReferralCode($user->id, $user->first_name);

            RewardPoint::create([
                'user_id' => $user->id,
                'referral_code' => $referralCode,
                'points' => 10, // Initial referral code points
                'transaction_type' => 'Referral',
                'status' => 1 // Active since the user is now verified
            ]);
        } else {
            // If referral already exists, just activate it
            $existingReferral->update(['status' => 1]);
        }

        // Update user status to verified
        $user->update(['status_id' => 1]);

        // Approve pending referral if any (referrer earns points)
        $referral = Referral::where('referred_id', $user->id)->where('status', 0)->first();
        if ($referral) {
            $referral->update(['status' => 1]); // Set referral to approved
        }

        $url = $this->container->get('router')->pathFor('login', [], ['message' => 'Account successfully verified! You can now log in.', 'status' => 'success']);
        return $response->withHeader('Location', $url)->withStatus(302);
    }



    /**
     * Generate a unique referral code.
     *
     * @param int $userId
     * @param string $firstName
     * @return string
     */
    private function generateReferralCode(int $userId, string $firstName): string
    {
        // Create a referral code with a prefix "BC" (BaziChic), user ID, and first name initials
        $prefix = "BC";
        $initials = strtoupper(substr($firstName, 0, 2));
        $uniqueId = strtoupper(bin2hex(random_bytes(3))); // Random 6-character hex string
        return "{$prefix}{$userId}{$initials}{$uniqueId}";
    }

    public function ResendVerification(Request $request, Response $response, $args): Response
    {
        $helper = new Helpers();
        $data = $request->getParsedBody();
        $email = $data['email'] ?? null;

        if (!$email) {
            return $response->withJson(['message' => 'Email is required.'], 400);
        }

        // Check if the user exists
        $user = User::where('email', $email)->first();
        if (!$user) {
            return $response->withJson(['message' => 'User not found.'], 404);
        }

        // Check if the user is already verified
        if ($user->status_id === 1) {
            return $response->withJson(['message' => 'Your account is already verified.'], 400);
        }

        // Generate a new verification token
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update or create the email verification record
        EmailVerifications::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $token, 'expires_at' => $expiresAt, 'verified_at' => null]
        );
        // Send the email
        $verificationLink = $_ENV['APP_URL'] . '/verify?token=' . $token;
        $subject = "Verify Your Account";
        $twig = $this->TwigTemplate();
        $template = $twig->render('verification-email.twig', ['user' => $user->name, 'verificationLink' => $verificationLink]);
        $emailResult = $helper->sendEmail($email, $subject, $template);
        if ($emailResult["status"] === 'success') {
            return $response->withJson(['message' => 'Verification link has been resent.'], 200);
        } else {
            return $response->withJson(['message' => 'Failed to send verification email.'], 500);
        }
    }
}
