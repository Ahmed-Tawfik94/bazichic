from flask import Blueprint, render_template

# Changed blueprint name and prefix for clarity and to avoid conflict if 'document' is used for viewing documents.
doc_actions_bp = Blueprint('doc_actions', __name__, url_prefix='/document-actions', template_folder='../../templates/document')

@doc_actions_bp.route('/<int:document_id>/save-progress', methods=['POST'])
def save_document_progress_post(document_id):
    return f"Hello from Document Actions - Save Progress for Document {document_id} (POST)!"

@doc_actions_bp.route('/<int:document_id>/reviews', methods=['POST'])
def add_document_review_post(document_id):
    return f"Hello from Document Actions - Add Review for Document {document_id} (POST)!"

@doc_actions_bp.route('/reviews/<int:review_id>/delete', methods=['POST'])
def delete_document_review_post(review_id):
    return f"Hello from Document Actions - Delete Review {review_id} (POST)!"

@doc_actions_bp.route('/<int:document_id>/endorse', methods=['POST'])
def endorse_document_post(document_id):
    return f"Hello from Document Actions - Endorse Document {document_id} (POST)!"
