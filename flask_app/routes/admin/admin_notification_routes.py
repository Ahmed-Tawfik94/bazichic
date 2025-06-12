from flask import Blueprint, render_template

admin_notification_bp = Blueprint('admin_notification', __name__, url_prefix='/admin/notifications', template_folder='../../../../templates/admin/notifications')

@admin_notification_bp.route('/', methods=['GET'])
def list_notifications_get():
    return "Admin Notifications: List Notifications (GET)"

@admin_notification_bp.route('/blast', methods=['POST'])
def blast_notification_post():
    return "Admin Notifications: Blast Notification (POST)"

@admin_notification_bp.route('/<int:notification_id>', methods=['DELETE']) # RESTful use of DELETE
def delete_notification_delete(notification_id):
    return f"Admin Notifications: Delete Notification {notification_id} (DELETE)"
