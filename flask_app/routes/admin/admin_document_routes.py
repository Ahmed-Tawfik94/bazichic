from flask import Blueprint, render_template

# template_folder path adjusted assuming templates/admin/documents/ will be created.
admin_doc_bp = Blueprint('admin_document', __name__, url_prefix='/admin/documents', template_folder='../../../../templates/admin/documents') # Corrected url_prefix

@admin_doc_bp.route('/select-document-type', methods=['GET'])
def select_document_type_get():
    return "Admin Docs: Select Document Type (GET)"

@admin_doc_bp.route('/manage', methods=['GET'])
def manage_documents_get():
    return "Admin Docs: Manage Documents (GET)"

@admin_doc_bp.route('/add/<string:doc_type>', methods=['GET'])
def add_document_get(doc_type):
    return f"Admin Docs: Add Document form for type '{doc_type}' (GET)"

@admin_doc_bp.route('/upload', methods=['POST'])
def upload_document_post():
    return "Admin Docs: Upload Document (POST)"

@admin_doc_bp.route('/edit/<string:qcode>', methods=['GET'])
def edit_document_get(qcode):
    return f"Admin Docs: Edit Document '{qcode}' form (GET)"

@admin_doc_bp.route('/update', methods=['POST'])
def update_document_post():
    return "Admin Docs: Update Document (POST)"

@admin_doc_bp.route('/book-detail/<int:doc_id>', methods=['GET'])
def view_book_detail_get(doc_id):
    return f"Admin Docs: View Book Detail for ID '{doc_id}' (GET)"

@admin_doc_bp.route('/delete', methods=['POST'])
def delete_document_post():
    return "Admin Docs: Delete Document (POST)"

@admin_doc_bp.route('/media/upload', methods=['POST'])
def upload_document_media_post():
    return "Admin Docs: Upload Document Media (POST)"

@admin_doc_bp.route('/audio/upload/<string:qcode>', methods=['POST'])
def upload_document_audio_post(qcode):
    return f"Admin Docs: Upload Document Audio for '{qcode}' (POST)"

@admin_doc_bp.route('/files/upload', methods=['POST'])
def upload_document_files_post():
    return "Admin Docs: Upload Document Files (POST)"

@admin_doc_bp.route('/manage-reviews', methods=['GET'])
def manage_reviews_get():
    return "Admin Docs: Manage Reviews (GET)"
