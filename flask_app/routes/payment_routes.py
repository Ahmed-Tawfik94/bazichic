from flask import Blueprint, render_template

payment_bp = Blueprint('payment', __name__, url_prefix='/payments', template_folder='../../templates/payment')

@payment_bp.route('/purchase/membership', methods=['POST'])
def purchase_membership_post():
    return "Hello from Payment Blueprint - Purchase Membership (POST)!"

@payment_bp.route('/success', methods=['GET'])
def payment_success_get():
    return "Hello from Payment Blueprint - Payment Success (GET)!"

@payment_bp.route('/cancel', methods=['GET'])
def payment_cancel_get():
    return "Hello from Payment Blueprint - Payment Cancel (GET)!"

@payment_bp.route('/webhook/stripe', methods=['POST'])
def stripe_webhook_post():
    return "Hello from Payment Blueprint - Stripe Webhook (POST)!"

# Changed original /invoice/{user_id}/{id} to use a string identifier for invoice_id
# as Stripe invoice IDs are typically strings (e.g., "in_1J...").
# The user_id might be for authorization/scoping, if needed.
@payment_bp.route('/invoice/<int:user_id>/<string:invoice_identifier>', methods=['GET'])
def view_invoice_get(user_id, invoice_identifier):
    return f"Hello from Payment Blueprint - View Invoice {invoice_identifier} for User {user_id} (GET)!"
