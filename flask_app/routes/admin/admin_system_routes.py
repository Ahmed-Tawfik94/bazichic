from flask import Blueprint, render_template

admin_system_bp = Blueprint('admin_system', __name__, url_prefix='/admin/system', template_folder='../../../../templates/admin/system')

@admin_system_bp.route('/configuration', methods=['GET'])
def view_configuration_get():
    return "Admin System: View Configuration (GET)"

@admin_system_bp.route('/configuration/update', methods=['POST'])
def update_configuration_post():
    return "Admin System: Update Configuration (POST)"

@admin_system_bp.route('/banners/upload', methods=['POST'])
def upload_banner_post():
    return "Admin System: Upload Banner (POST)"

@admin_system_bp.route('/banners', methods=['GET'])
def list_banners_get():
    return "Admin System: List Banners (GET)"
