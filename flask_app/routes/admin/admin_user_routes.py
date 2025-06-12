from flask import Blueprint, render_template

admin_user_bp = Blueprint('admin_user', __name__, url_prefix='/admin/users', template_folder='../../../../templates/admin/users')

@admin_user_bp.route('/manage', methods=['GET'])
def manage_users_get():
    return "Admin Users: Manage Users (GET)"

@admin_user_bp.route('/retrieve', methods=['GET']) # For AJAX table loading
def retrieve_users_get():
    return "Admin Users: Retrieve Users List (AJAX GET)"

@admin_user_bp.route('/create', methods=['GET'])
def create_user_get():
    return "Admin Users: Create New User form (GET)"

@admin_user_bp.route('/create', methods=['POST'])
def create_user_post():
    return "Admin Users: Create New User (POST)"

@admin_user_bp.route('/edit/<int:user_id>', methods=['GET']) # Covers /profile/{username} (lookup by ID then username) and /edit-user-profile
def edit_user_get(user_id):
    return f"Admin Users: Edit User {user_id} form (GET)"

@admin_user_bp.route('/edit/<int:user_id>', methods=['POST']) # Covers /profile/update/{id}
def edit_user_post(user_id):
    return f"Admin Users: Update User {user_id} (POST)"

@admin_user_bp.route('/view/<string:username_or_id>', methods=['GET']) # Covers /view-profile/{username} and /preview/{id}
def view_user_get(username_or_id):
    return f"Admin Users: View User Profile for '{username_or_id}' (GET)"

@admin_user_bp.route('/delete', methods=['POST']) # Should take user_id, typically in form data or as <int:user_id>
def delete_user_post():
    # user_id = request.form.get('user_id') or request.args.get('user_id')
    return f"Admin Users: Delete User (POST)!"

@admin_user_bp.route('/reset-password', methods=['POST']) # Should take user_id
def reset_user_password_post():
    return "Admin Users: Reset User Password (POST)!"

@admin_user_bp.route('/update-credentials', methods=['POST']) # For admin's own password, or a specific user? Assumed admin's own for now.
def update_admin_credentials_post(): # Renamed for clarity if it's admin's own creds.
    return "Admin Users: Update Admin Credentials (POST)!"

# This was /admin/payments/invoice/{user_id}/{id} in PHP admin routes.
# Placing it here as it's user-centric.
@admin_user_bp.route('/<int:user_id>/invoice/<string:invoice_identifier>', methods=['GET'])
def view_user_invoice_admin_get(user_id, invoice_identifier):
    return f"Admin Users: View Invoice {invoice_identifier} for User {user_id} (GET)!"
