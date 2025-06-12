from flask import Blueprint, render_template

admin_main_bp = Blueprint('admin_main', __name__, url_prefix='/admin', template_folder='../../../templates/admin')

@admin_main_bp.route('/')
def dashboard():
    return "Hello from Admin Main Blueprint - Dashboard!"

@admin_main_bp.route('/contact-submissions', methods=['GET'])
def view_contact_submissions_get():
    return "Admin Main: View Contact Submissions (GET)"

@admin_main_bp.route('/transactions/delete', methods=['POST']) # General transaction delete, might need more specific context
def delete_transactions_post():
    return "Admin Main: Delete Transactions (POST)"
