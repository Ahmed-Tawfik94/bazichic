from flask import Blueprint, render_template

dashboard_bp = Blueprint('dashboard', __name__, url_prefix='/dashboard', template_folder='../../templates/dashboard')

@dashboard_bp.route('/')
def user_dashboard():
    return "Hello from Dashboard Blueprint - User Dashboard!"

@dashboard_bp.route('/profile', methods=['GET'])
def my_profile_get():
    return "Hello from Dashboard Blueprint - My Profile (GET)!"

@dashboard_bp.route('/profile/update', methods=['POST'])
def my_profile_post():
    return "Hello from Dashboard Blueprint - My Profile Update (POST)!"

@dashboard_bp.route('/credentials/update', methods=['POST'])
def update_my_credentials_post():
    return "Hello from Dashboard Blueprint - Update My Credentials (POST)!"

@dashboard_bp.route('/bookmarks')
def my_bookmarks_get():
    return "Hello from Dashboard Blueprint - My Bookmarks (GET)!"

@dashboard_bp.route('/saved-reads', methods=['GET'])
def my_saved_reads_get():
    return "Hello from Dashboard Blueprint - My Saved Reads (GET)!"

@dashboard_bp.route('/saved-reads', methods=['POST'])
def my_saved_reads_post():
    return "Hello from Dashboard Blueprint - My Saved Reads (POST)!"

@dashboard_bp.route('/referral-codes-summary')
def my_referral_codes_dashboard_get():
    return "Hello from Dashboard Blueprint - My Referral Codes Summary (GET)!"

@dashboard_bp.route('/my-connections')
def my_connections_dashboard_get():
    return "Hello from Dashboard Blueprint - My Connections (GET)!"

@dashboard_bp.route('/notifications', methods=['GET'])
def list_my_notifications_get():
    return "Hello from Dashboard Blueprint - List My Notifications (GET)!"

@dashboard_bp.route('/notifications/read/<int:notification_id>', methods=['POST'])
def mark_notification_read_post(notification_id):
    return f"Hello from Dashboard Blueprint - Mark Notification {notification_id} as Read (POST)!"

@dashboard_bp.route('/billing-portal', methods=['POST'])
def create_billing_portal_session_post():
    return "Hello from Dashboard Blueprint - Create Billing Portal Session (POST)!"
