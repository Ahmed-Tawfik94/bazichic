from flask import Blueprint, render_template

admin_faq_bp = Blueprint('admin_faq', __name__, url_prefix='/admin/faqs', template_folder='../../../../templates/admin/faqs') # Corrected prefix

# FAQ Management
@admin_faq_bp.route('/manage', methods=['GET'])
def manage_faqs_get():
    return "Admin FAQs: Manage FAQs (GET)"

@admin_faq_bp.route('/add', methods=['GET'])
def add_faq_get():
    return "Admin FAQs: Add FAQ form (GET)"

@admin_faq_bp.route('/edit/<int:faq_id>', methods=['GET'])
def edit_faq_get(faq_id):
    return f"Admin FAQs: Edit FAQ {faq_id} form (GET)"

@admin_faq_bp.route('/<int:faq_id>', methods=['GET']) # PHP: /faq/{id}
def view_faq_get(faq_id):
    return f"Admin FAQs: View FAQ {faq_id} (GET)"

# FAQ Category Management
@admin_faq_bp.route('/categories/manage', methods=['GET']) # PHP: /faqs-category-manager
def manage_faq_categories_get():
    return "Admin FAQs: Manage FAQ Categories (GET)"

@admin_faq_bp.route('/categories/add', methods=['GET']) # PHP: /add-faq-category
def add_faq_category_get():
    return "Admin FAQs: Add FAQ Category form (GET)"

@admin_faq_bp.route('/categories/edit/<int:category_id>', methods=['GET']) # PHP: /edit-faq-category/{id}
def edit_faq_category_get(category_id):
    return f"Admin FAQs: Edit FAQ Category {category_id} form (GET)"

# FAQ SubCategory Management
@admin_faq_bp.route('/subcategories/add', methods=['GET']) # PHP: /add-faq-subcategory
def add_faq_subcategory_get():
    return "Admin FAQs: Add FAQ SubCategory form (GET)"

@admin_faq_bp.route('/subcategories/edit/<int:subcategory_id>', methods=['GET']) # PHP: /edit-faq-subcategory/{id}
def edit_faq_subcategory_get(subcategory_id):
    return f"Admin FAQs: Edit FAQ SubCategory {subcategory_id} form (GET)"

# API-like routes (from PHP /apis group)
@admin_faq_bp.route('/api/categories/create', methods=['POST'])
def api_create_faq_category_post():
    return "Admin FAQs API: Create FAQ Category (POST)"

@admin_faq_bp.route('/api/categories/update', methods=['POST'])
def api_update_faq_category_post():
    return "Admin FAQs API: Update FAQ Category (POST)"

@admin_faq_bp.route('/api/categories/delete', methods=['POST'])
def api_delete_faq_category_post():
    return "Admin FAQs API: Delete FAQ Category (POST)"

@admin_faq_bp.route('/api/subcategories/create', methods=['POST'])
def api_create_faq_subcategory_post():
    return "Admin FAQs API: Create FAQ SubCategory (POST)"

@admin_faq_bp.route('/api/subcategories/update', methods=['POST'])
def api_update_faq_subcategory_post():
    return "Admin FAQs API: Update FAQ SubCategory (POST)"

@admin_faq_bp.route('/api/subcategories/delete', methods=['POST'])
def api_delete_faq_subcategory_post():
    return "Admin FAQs API: Delete FAQ SubCategory (POST)"

@admin_faq_bp.route('/api/faqs/create', methods=['POST'])
def api_create_faq_post():
    return "Admin FAQs API: Create FAQ (POST)"

@admin_faq_bp.route('/api/faqs/update', methods=['POST'])
def api_update_faq_post():
    return "Admin FAQs API: Update FAQ (POST)"

@admin_faq_bp.route('/api/faqs/delete', methods=['POST'])
def api_delete_faq_post():
    return "Admin FAQs API: Delete FAQ (POST)"

@admin_faq_bp.route('/api/subcategories/list/<int:category_id>', methods=['GET']) # PHP: /list/{category}
def api_list_faq_subcategories_get(category_id):
    return f"Admin FAQs API: List Subcategories for Category {category_id} (GET)"
