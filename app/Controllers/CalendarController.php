<?php

namespace App\Controllers;
use App\Models\Plan;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;

class CalendarController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        if (isset($_SESSION["userID"])){

        $user= User::find($_SESSION["userID"]);
        $user_sub = $user->subscription;
            $plans = Plan::where('is_available', 1)->get()->groupBy('name')->map(function ($items) {
                return  $items->pluck('id')->toArray() ;// Array of IDs under this name
            });
        }

        $vars = [
            'page' => [
                'title' => 'Calendar',
                'description' => 'Update Calendar',
                'user_dob' => $user->dob ?? null,
                'user_subscription' => $user_sub ?? null,
                'plans_ids' => $plans ?? []
            ]
        ];
        return $this->view->render($response, 'calendar/bazi.twig', $vars);
    }
    public function getSubscription(Request $request, Response $response, $args)
    {
        if (isset($_SESSION["userID"])) {
            $user= User::find($_SESSION["userID"]);
            $user_sub = $user->subscription;
            $output = [
                    'user_subscription' => $user_sub
            ];
            return $this->jsonResponse($response,$output);
        } else  {
            return $this->jsonResponse($response, [ 'user_subscription' => null], 404);
        }
    }
    public function ManageReviews(Request $request, Response $response, $args)
    {}

    public function manageUsers(Request $request, Response $response, $args)
    {}


    public function UploadPanel(Request $request, Response $response, $args)
    {}

    public function systemConfiguration(Request $request, Response $response, $args)
    {}

    public function Uploadsettings(Request $request, Response $response, $args)
    {}

    public function viewProfile(Request $request, Response $response, $args)
    {}
    public function FreeTrialsSummary(Request $request, Response $response, $args)
    {}
    public function GrantTrial(Request $request, Response $response, $args)
    {}
    function Timeline(Request $request, Response $response, $args)
    {}
    function ViewContactSubmision(Request $request, Response $response, $args)
    {}
}
