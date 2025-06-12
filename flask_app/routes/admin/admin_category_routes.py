from flask import Blueprint, render_template

# Blueprint for general document categories (not FAQ categories)
admin_cat_bp = Blueprint('admin_category', __name__, url_prefix='/admin/categories', template_folder='../../../../templates/admin/categories') # Corrected prefix

@admin_cat_bp.route('/manage', methods=['GET']) # PHP: /manage-categories
def manage_categories_get():
    return "Admin Categories: Manage Categories (GET)"

@admin_cat_bp.route('/add', methods=['GET'])
def add_category_get():
    return "Admin Categories: Add Category form (GET)"

@admin_cat_bp.route('/create', methods=['POST'])
def create_category_post():
    return "Admin Categories: Create Category (POST)"

@admin_cat_bp.route('/edit/<int:category_id>', methods=['GET']) # PHP: /edit/{id}
def edit_category_get(category_id):
    return f"Admin Categories: Edit Category {category_id} form (GET)"

@admin_cat_bp.route('/update', methods=['POST'])
def update_category_post():
    return "Admin Categories: Update Category (POST)"

@admin_cat_bp.route('/delete', methods=['POST'])
def delete_category_post():
    return "Admin Categories: Delete Category (POST)"
