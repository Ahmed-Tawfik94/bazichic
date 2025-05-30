<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Helpers\Constants;
use App\Helpers\Helpers;
use App\Models\EmailVerifications;
use App\Models\Notification;
use App\Models\Referral;
use App\Models\RewardPoint;
use App\Models\User;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Added for Slim 4
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use Slim\Interfaces\RouteParserInterface;
use Illuminate\Database\Capsule\Manager as Capsule; // Assuming this is the DB service type
use SimpleFlash\Flash; // Added for flash messages

abstract class BaseController
{
    protected ContainerInterface $container; // Keep for now
    protected Twig $twig; // Changed from generic 'view'
    protected Capsule $db; // Assuming Capsule is the DB service type
    protected RouteParserInterface $routeParser; // Changed from generic 'router'
    protected LoggerInterface $logger;
    protected Flash $flash; // Added for flash messages

    public function __construct(
        ContainerInterface $container, // Keep for now, for phased refactoring of child classes
        Twig $twig,
        Capsule $db, // Or your DB service type
        RouteParserInterface $routeParser,
        LoggerInterface $logger,
        Flash $flash // Injected Flash service
    ) {
        $this->container = $container; // Keep for children that might still use it
        $this->twig = $twig;
        $this->db = $db;
        $this->routeParser = $routeParser;
        $this->logger = $logger;
        $this->flash = $flash; // Store Flash service
    }

    function jsonResponse($response, array $data, $status = 200) {
        $response = $response->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
        $response->getBody()->write(json_encode($data));

        return $response;
    }
    // Removed TwigTemplate() method
    /**
     * @param $first_name
     * @param $user_id
     * @param string $user_name
     * @param $date_created
     * @param $note
     * @return string
     */
    public function sendNotification($first_name, $user_id, string $user_name, $date_created, $note): array
    {
        $output = array();
        $title = 'Welcome to BaziChic';
        $message = 'Hi ' . $first_name . '! Thanks for registering your account with BaziChic.';
        $noti_res = (new Notification)->createNotification((object)[
            'sender_id' => $user_id,
            'title' =>  $title,
            'message' => $message,
            'seen' => 0,]);
        if ($noti_res["code"] == Constants::INSERT_SUCCESS) {
            $output['note'] = " Notified successfully.";
        } else {
            $output['note'] = $noti_res["msg"];
        }
        return $output;
    }
    /**
     * @param $user_id
     * @param $first_name
     * @param Helpers $helper
     * @param $email
     * @param array $output
     * @return array
     */
    public function VerificationLink($user_id, $first_name, $email, array $output): array
    {
        try {
            $helper = new Helpers();
            $token = bin2hex(random_bytes(16));
            $baseUrl = $_ENV['APP_URL'];
            $generated_token = EmailVerifications::createVerification($user_id, $token);
            if ($generated_token) {
                $subject = "Welcome to BaziChic";
                // Use the injected $this->twig instance
                // Assuming 'email/' is a namespace or subdirectory within the main Twig path
                $template = $this->twig->getEnvironment()->render('email/welcome-email.twig', [
                    'first_name' => $first_name,
                    'token' => $token,
                    'app_url' => $_ENV['APP_URL']
                ]);
                $emailResult = $helper->sendEmail($email, $subject, $template);
                if ($emailResult["status"] === 'success') {
                    $output["message"] .= " We have sent further instructions to your registered email address.";
                } else {
                    $output["message"] = $emailResult["message"];
                }
            }


        } catch (\Exception $e) {
            $output["note"] .= " Error notifying." . $e->getMessage();
        }
        return $output;
    }
    /**
     * @param int $ref_user_id
     * @param $referral_code
     * @param $user_id
     * @param array $output
     * @param $date_created
     * @return void
     */
    public function RecordActivity(int $ref_user_id, $referral_code, $user_id, array $output, $date_created): void
    {
        try {
            if (is_numeric($ref_user_id) && $ref_user_id > 0) {
                if (Referral::isReferralCodeExist($referral_code)) {
                    //Update Referral
                    $ref_user_id = Referral::getUserID($referral_code);
                    $date_updated = date('Y-m-d H:i:s');
                    Referral::updateReferral($referral_code, $status = "Used", $date_updated);
                    User::updateOrFail($user_id, $ref_user_id);
                    $referredUser = User::getNameByID($user_id);
                    $referringUser = User::getNameByID($ref_user_id);
                    $output['message'] .= ' The Referral Code has been applied.';
                    $output["note"] .= "Referrals Updated.";
                    $ref_message = "Your referral code " . $referral_code . " from " . $referringUser . " has been applied successfully.";
                    //Notify New User
                    if (!empty($referredUser)) {
                        (new Notification)->createNotification((object)['sender_id'=>$ref_user_id,
                            'title'=>"Referral Code Applied",
                            'message'=>$ref_message,
                            'data_title'=>"ReferralApplied",'data_id'=>$referral_code,'seen'=>0]);
                        $output["note"] .= " Referred user Notified. ";
                    }
                    //Notify Referrering User
                    if (!empty($referringUser)) {
                        $con_message = "Congrats! You have a new connection. " . $referredUser . " just used your referral code to register an account.";
                        (new Notification)->createNotification((object)['sender_id'=>$user_id,
                            'title'=>"New Connection",
                            'message'=>$ref_message,
                            'data_title'=>"ReferralApplied",'data_id'=>$referral_code,'seen'=>0]    );
                        $output["note"] .= " Referring User notified.";
                    }
                    //Process Reward Points
                    $note = $referredUser . " registered a new account using code " . $referral_code . ".";
                    $rewarding_res = RewardPoint::createRewardPoint((object)
                    [
                        'user_id' => $ref_user_id,
                        'points'=>5,
                        'transaction_type'=>"Referral Bonus",
                        'status'=>"Completed",

                    ]);
                    if ($rewarding_res["code"] == Constants::INSERT_SUCCESS) {
                        $output["note"] .= " Reward point processed successfully.";
                    } else {
                        $output["note"] .= " Failed to process Reward point. " . $rewarding_res["msg"];
                    }
                } else {
                    //$output['error'] = true;
                    $output['message'] .= 'The Referral Code you applied was invalid.';
                    $output["note"] .= "Invalid Referral Code.";
                }
            }
        } catch (\Exception $e) {
            $output["note"] .= " ERROR DURING REFERRAL PROCESS: " . $e->getMessage();
        }
    }
}
