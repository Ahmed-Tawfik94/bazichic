from flask import Blueprint, render_template

referral_bp = Blueprint('referral', __name__, url_prefix='/referrals', template_folder='../../templates/referral')

@referral_bp.route('/', methods=['GET'])
def list_my_referrals_get():
    return "Hello from Referral Blueprint - List My Referrals (GET)!"

@referral_bp.route('/generate-code', methods=['POST'])
def generate_referral_code_post():
    return "Hello from Referral Blueprint - Generate Referral Code (POST)!"

@referral_bp.route('/redeem-reward', methods=['POST'])
def redeem_reward_post():
    return "Hello from Referral Blueprint - Redeem Reward (POST)!"

@referral_bp.route('/request-withdrawal', methods=['POST'])
def request_withdrawal_post():
    return "Hello from Referral Blueprint - Request Withdrawal (POST)!"

@referral_bp.route('/approve-referral', methods=['POST']) # Potentially admin-only
def approve_referral_post():
    return "Hello from Referral Blueprint - Approve Referral (POST)!"
