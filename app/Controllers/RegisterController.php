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

// Added for Slim 4
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use Slim\Interfaces\RouteParserInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Validation\Factory as ValidatorFactory; // Added for validation

class RegisterController extends BaseController
{
    protected ValidatorFactory $validatorFactory;

    // Constructor matching the new BaseController signature
    public function __construct(
        ContainerInterface $container,
        Twig $twig,
        Capsule $db,
        RouteParserInterface $routeParser,
        LoggerInterface $logger,
        ValidatorFactory $validatorFactory // Injected validator factory
        // StripeService $stripeService // Uncomment and add if StripeService is used directly here
    ) {
        parent::__construct($container, $twig, $db, $routeParser, $logger);
        $this->validatorFactory = $validatorFactory;
        // $this->stripeService = $stripeService; // Uncomment if StripeService is used directly here
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        if (isset($_SESSION['userID'])) {
            $url = $this->routeParser->urlFor('dashboard');
            return $response->withHeader('Location', $url)->withStatus(302);
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
        return $this->twig->render($response, 'register.twig', $vars);
    }

    public function register(Request $request, Response $response, array $args): Response
    {
        $params = (array)$request->getParsedBody();
        $output = array();
        $output["note"] = "";
        
        $data = $params; // Use the already cast $params as $data for validation

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email_reg' => 'required|email', // Consider 'unique:users,email' if DB presence verifier is setup
            'dob' => 'required|date', 
            'country' => 'required|string',
            'password' => 'required|string|min:6', // 'confirmed' rule expects 'password_confirmation'
            'password_repeat' => 'required|same:password', // Manual check for password_repeat
            // 'referral_code' => 'nullable|string' // Basic check, DB check later
        ];

        $validator = $this->validatorFactory->make($data, $rules, [
            'password_repeat.same' => 'Password and Confirm Password do not match.'
        ]);

        if ($validator->fails()) {
            $firstMessage = $validator->errors()->all()[0] ?? 'Validation failed. Please check your input.';
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => $firstMessage
                // Optionally, pass all errors: 'errors' => $validator->errors()->toArray()
            ], 400);
        }

        // Extracted validated data (though not strictly necessary here as we use $params directly later)
        // $validatedData = $validator->validated();

        $referral_code = $params['referral_code'] ?? '';
        $ref_user_id = 0;

        // Manual Check for referral code as DB presence verifier is not assumed active
        if (!empty($referral_code)) {
            $referrerReward = RewardPoint::where('referral_code', $referral_code)->first();
            if (!$referrerReward) {
                return $this->jsonResponse($response, [
                    'error' => true,
                    'message' => 'Invalid referral code. Please try again.'
                ], 400);
            }
            $ref_user_id = $referrerReward->user_id;
        }

        $phone = ""; // Remains empty as per original logic
        $date_created = date('Y-m-d H:i:s');
        $api_key = Util::generateApiKey();
        // $user_name = Util::createNewUsername(8); // User model seems to generate this

        // Register user (passing validated data, or original params if preferred)
        $res = User::register((object)[
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'phone' => $phone,
            'email' => $params['email_reg'],
            'password' => $params['password'],
            'dob' => $params['dob'],
            'country' => $params['country'],
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
            $output["user_name"] = $res["userName"]; // Use userName from User::register response

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


    public function VerifyAccount(Request $request, Response $response, array $args): Response
    {
        $params = $request->getQueryParams();
        $url = $this->routeParser->urlFor('login'); // Define login URL once

        if (!isset($params['token'])) {
            $this->flash->error('Token not provided.');
            return $response->withHeader('Location', $url)->withStatus(302);
        }

        $token = $params['token'];
        $res = EmailVerifications::verify($token);

        if ($res['status'] !== 'success') {
            $this->flash->error($res['message'] ?? 'Verification failed.');
            return $response->withHeader('Location', $url)->withStatus(302);
        }

        $user = User::find($res['user_id']);
        if (!$user) {
            $this->flash->error('User not found.');
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
        
        $this->flash->success('Account successfully verified! You can now log in.');
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

    public function ResendVerification(Request $request, Response $response, array $args): Response
    {
        $helper = new Helpers();
        $data = (array)$request->getParsedBody();
        $email = $data['email'] ?? null;

        if (!$email) {
            // Use $this->jsonResponse from BaseController
            return $this->jsonResponse($response, ['message' => 'Email is required.'], 400);
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
        // Use $this->twig from BaseController
        $template = $this->twig->getEnvironment()->render('email/verification-email.twig', [
            'user' => $user->first_name, // Assuming 'name' property exists, or use 'first_name'
            'verificationLink' => $verificationLink
        ]);
        $emailResult = $helper->sendEmail($email, $subject, $template);
        if ($emailResult["status"] === 'success') {
            // Use $this->jsonResponse from BaseController
            return $this->jsonResponse($response, ['message' => 'Verification link has been resent.'], 200);
        } else {
            // Use $this->jsonResponse from BaseController
            return $this->jsonResponse($response, ['message' => 'Failed to send verification email.'], 500);
        }
    }
}
