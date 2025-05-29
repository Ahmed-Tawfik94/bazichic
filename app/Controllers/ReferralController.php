<?php

namespace App\Controllers;

use App\Models\RedeemTransaction;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;
use App\Models\Referral;
use App\Models\RewardPoint;

class ReferralController
{
    /**
     * Generate a referral code for the logged-in user.
     */
    public function generateCode(Request $request, Response $response): Response
    {
        // Get the logged-in user's ID from the session
        $userId = $_SESSION['userID']; // Adjust based on your session structure
        $user = User::find($userId);

        if (!$user) {
            return $response->withJson([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        // Check if the user already has a referral code in reward_points
        $existingReferral = RewardPoint::where('user_id', $userId)->where('transaction_type', 'Referral')->first();

        if ($existingReferral) {
            return $response->withJson([
                'success' => false,
                'message' => 'Referral code already exists.'
            ], 400);
        }

        try {
            // Generate a unique referral code
            $referralCode = $this->generateReferralCode($userId, $user->first_name);

            // Store the referral code in reward_points
            $rewardPoint = new RewardPoint();
            $rewardPoint->user_id = $userId;
            $rewardPoint->referral_code = $referralCode;
            $rewardPoint->points = 10; // Referral code grants 10 points
            $rewardPoint->transaction_type = 'Referral'; // Mark as referral
            $rewardPoint->status = 1; // Set as PENDING (0) until admin approves
            $rewardPoint->save();

            // Update the session
            $_SESSION['referral_code'] = $referralCode;

            return $response->withJson([
                'success' => true,
                'referral_code' => $referralCode,
                'message' => 'Referral code generated successfully and is pending approval.'
            ]);
        } catch (Exception $e) {
            return $response->withJson([
                'success' => false,
                'message' => 'Failed to generate referral code. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function approveReferral(Request $request, Response $response, array $args): Response
    {
        $referralId = $args['id']; // Get referral ID from request

        $referral = Referral::find($referralId);

        if (!$referral) {
            return $response->withJson([
                'success' => false,
                'message' => 'Referral not found.'
            ], 404);
        }

        try {
            // Mark referral as approved
            $referral->status = 1;
            $referral->save();

            // Update reward_points status to Active
            $rewardPoint = RewardPoint::find($referral->reward_point_id);
            if ($rewardPoint) {
                $rewardPoint->status = 1; // Active
                $rewardPoint->save();
            }

            return $response->withJson([
                'success' => true,
                'message' => 'Referral approved successfully.'
            ]);
        } catch (Exception $e) {
            return $response->withJson([
                'success' => false,
                'message' => 'Failed to approve referral.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Fetch referrals for the logged-in user.
     */
    public function getReferrals(Request $request, Response $response): Response
    {
        $userId = $_SESSION['userID']; // Adjust based on your session structure

        try {
            $referrals = Referral::getAllMyReferrals($userId);

            return $response->withJson([
                'success' => true,
                'data' => $referrals
            ]);
        } catch (Exception $e) {
            return $response->withJson([
                'success' => false,
                'message' => 'Failed to fetch referrals.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a referral (called after referred user verification).
     */
    public function approveReferralOld(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();
        $referredId = $params['referred_id'] ?? null;

        if (!$referredId) {
            return $response->withJson([
                'success' => false,
                'message' => 'Referred user ID is required.'
            ], 400);
        }

        try {
            Referral::approveReferral($referredId);

            return $response->withJson([
                'success' => true,
                'message' => 'Referral approved successfully.'
            ]);
        } catch (Exception $e) {
            return $response->withJson([
                'success' => false,
                'message' => 'Failed to approve referral.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a unique referral code.
     */
    private function generateReferralCode(int $userId, string $firstName): string
    {
        $prefix = "BC";
        $initials = strtoupper(substr($firstName, 0, 2));
        $uniqueId = strtoupper(bin2hex(random_bytes(3)));
        return "{$prefix}{$userId}{$initials}{$uniqueId}";
    }

    public function redeemReward(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();
        $userId = $params['user_id'];

        // Check if the user has enough points
        $currentPoints = RewardPoint::getCurrentRewardPointFor($userId);
        if ($currentPoints < 100) {
            return $response->withJson([
                'success' => false,
                'message' => 'You need at least 100 points to redeem a reward.'
            ], 400);
        }

        try {
            // Deduct 100 points
            RedeemTransaction::create([
                'user_id' => $userId,
                'points' => 100,
                'type' => 'redeem_coupon',
                'status' => 1 // Approved instantly
            ]);

            return $response->withJson([
                'success' => true,
                'message' => 'Reward redeemed successfully!'
            ]);
        } catch (Exception $e) {
            return $response->withJson([
                'success' => false,
                'message' => 'Failed to redeem reward.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function withdrawMoney(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();
        $userId = $params['user_id'];

        // Check if the user has enough points
        $currentPoints = RewardPoint::getCurrentRewardPointFor($userId);
        if ($currentPoints < 1000) {
            return $response->withJson([
                'success' => false,
                'message' => 'You need at least 1000 points to withdraw money.'
            ], 400);
        }

        try {
            // Deduct 1000 points
            RedeemTransaction::create([
                'user_id' => $userId,
                'points' => 1000,
                'type' => 'withdraw_money',
                'status' => 0 // Pending approval
            ]);

            return $response->withJson([
                'success' => true,
                'message' => 'Withdrawal request submitted for approval.'
            ]);
        } catch (Exception $e) {
            return $response->withJson([
                'success' => false,
                'message' => 'Failed to process withdrawal request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function requestWithdrawMoney(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();
        $userId = $params['user_id'];
        $withdrawAmount = isset($params['amount']) ? floatval($params['amount']) : 0;
        $pointsRequired = $withdrawAmount * 10;

        // Validate amount
        if ($withdrawAmount < 100) {
            return $response->withJson([
                'success' => false,
                'message' => 'Minimum withdrawal amount is $100.'
            ], 400);
        }

        // Check if user has enough points
        $currentPoints = RewardPoint::getCurrentRewardPointFor($userId);
        if ($currentPoints < $pointsRequired) {
            return $response->withJson([
                'success' => false,
                'message' => 'You do not have enough points for this withdrawal.'
            ], 400);
        }

        try {
            // Create withdrawal request (Pending approval)
            RedeemTransaction::create([
                'user_id' => $userId,
                'points' => $pointsRequired,
                'type' => 'withdraw_money',
                'status' => 1 // Pending approval
            ]);

            return $response->withJson([
                'success' => true,
                'message' => "Your withdrawal request of \${$withdrawAmount} has been submitted for approval."
            ]);
        } catch (Exception $e) {
            return $response->withJson([
                'success' => false,
                'message' => 'Failed to submit withdrawal request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
