from flask import Blueprint, render_template

subscription_bp = Blueprint('subscription', __name__, url_prefix='/subscription', template_folder='../../templates/subscription')

@subscription_bp.route('/subscription-plans')
def list_subscription_plans():
    return "Hello from Subscription Blueprint - List Subscription Plans!"

@subscription_bp.route('/confirm-subscription/<string:ref_code>')
def confirm_subscription(ref_code):
    return f"Hello from Subscription Blueprint - Confirm Subscription with ref_code: {ref_code}!"

@subscription_bp.route('/my-subscriptions', methods=['GET'])
def get_my_subscriptions():
    return "Hello from Subscription Blueprint - Get My Subscriptions (GET)!"

@subscription_bp.route('/update-subscriptions', methods=['POST'])
def update_my_subscriptions_post():
    return "Hello from Subscription Blueprint - Update My Subscriptions (POST)!"
