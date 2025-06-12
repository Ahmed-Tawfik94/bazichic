from flask import Blueprint, render_template

ebook_bp = Blueprint('ebook', __name__, url_prefix='/e-book-store', template_folder='../../templates/ebook')

@ebook_bp.route('/')
def list_ebooks():
    return "Hello from Ebook Blueprint - List Ebooks!"

@ebook_bp.route('/book-detail/<int:ebook_id>')
def ebook_detail(ebook_id):
    return f"Hello from Ebook Blueprint - Ebook Detail for ID: {ebook_id}!"

@ebook_bp.route('/ebook-reader', methods=['POST']) # PHP route was POST
def ebook_reader_post():
    return "Hello from Ebook Blueprint - Ebook Reader (POST)!"
