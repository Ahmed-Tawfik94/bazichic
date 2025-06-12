from flask import Blueprint, render_template

admin_reward_bp = Blueprint('admin_reward', __name__, url_prefix='/admin/rewards', template_folder='../../../../templates/admin/rewards')

@admin_reward_bp.route('/points/summary', methods=['GET']) # Corresponds to /admin/reward-points
def reward_points_summary_get():
    return "Admin Rewards: Reward Points Summary (GET)"

@admin_reward_bp.route('/points/assign', methods=['POST']) # Corresponds to /admin/assign-reward-points (assuming POST)
def assign_reward_points_post(): # Changed from GET to POST as it implies an action
    return "Admin Rewards: Assign Reward Points (POST)"

@admin_reward_bp.route('/referrals/create-code', methods=['POST']) # Corresponds to /admin/referrals/create
def create_referral_code_admin_post(): # Renamed to avoid conflict if user-facing one exists
    return "Admin Rewards: Create Referral Code (Admin) (POST)"

@admin_reward_bp.route('/referrals/manage', methods=['GET']) # Corresponds to /admin/referrals/manage
def manage_referrals_admin_get():
    return "Admin Rewards: Manage Referrals (Admin) (GET)"

@admin_reward_bp.route('/referrals/retrieve', methods=['GET']) # Added for /admin/referrals/retrieve
def retrieve_referrals_admin_get():
    return "Admin Rewards: Retrieve Referrals (Admin AJAX) (GET)"

@admin_reward_bp.route('/referrals/transactions', methods=['GET']) # Corresponds to /admin/referrals/transactions
def referral_transactions_admin_get():
    return "Admin Rewards: Referral Transactions (Admin) (GET)"

@admin_reward_bp.route('/referrals/transactions/retrieve', methods=['GET']) # Added for /admin/referrals/retrieve-transactions
def retrieve_referral_transactions_admin_get():
    return "Admin Rewards: Retrieve Referral Transactions (Admin AJAX) (GET)"

@admin_reward_bp.route('/redeem-transactions/manage', methods=['GET']) # Corresponds to /admin/redeem-transactions/manage
def manage_redeem_transactions_admin_get():
    return "Admin Rewards: Manage Redeem Transactions (Admin) (GET)"

@admin_reward_bp.route('/redeem-transactions/retrieve', methods=['GET']) # Added for /admin/redeem-transactions/retrieve
def retrieve_redeem_transactions_admin_get():
    return "Admin Rewards: Retrieve Redeem Transactions (Admin AJAX) (GET)"
