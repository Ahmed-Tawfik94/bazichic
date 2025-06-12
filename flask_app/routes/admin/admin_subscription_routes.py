from flask import Blueprint, render_template

admin_subscription_bp = Blueprint('admin_subscription', __name__, url_prefix='/admin/subscriptions', template_folder='../../../../templates/admin/subscriptions')

@admin_subscription_bp.route('/manage', methods=['GET'])
def manage_subscriptions_get():
    return "Admin Subscriptions: Manage All Subscriptions (GET)"

@admin_subscription_bp.route('/assign', methods=['POST'])
def assign_subscription_post():
    return "Admin Subscriptions: Assign Subscription (POST)"
