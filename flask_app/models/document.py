from flask_app.extensions import db  # Import db from extensions
from sqlalchemy import func # For default timestamps

class Document(db.Model):
    __tablename__ = 'documents'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(200), nullable=False)
    description = db.Column(db.Text, nullable=True) # Made nullable

    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False, index=True)
    user = db.relationship('User', backref=db.backref('documents', lazy='dynamic'))

    qcode = db.Column(db.String(50), unique=True, nullable=True, index=True)
    link = db.Column(db.String(500), nullable=True) # Renamed from file_path
    cover = db.Column(db.String(255), nullable=True) # Path or URL to cover image
    is_downloadable = db.Column(db.Boolean, default=False)

    document_type_id = db.Column(db.Integer, db.ForeignKey('document_types.id', name='fk_document_document_type_id', use_alter=True), nullable=True, index=True)
    document_type = db.relationship('DocumentType', back_populates='documents')

    category_id = db.Column(db.Integer, db.ForeignKey('categories.id', name='fk_document_category_id', use_alter=True), nullable=True, index=True)
    category = db.relationship('Category', back_populates='documents')

    author_name = db.Column(db.String(150), nullable=True)
    author_link = db.Column(db.String(255), nullable=True)
    author_desc = db.Column(db.Text, nullable=True)
    num_pages = db.Column(db.Integer, nullable=True)
    price = db.Column(db.Float, nullable=True) # Or db.Numeric for fixed precision
    listen_time = db.Column(db.String(50), nullable=True) # E.g., "2h 30m"
    read_time = db.Column(db.String(50), nullable=True)   # E.g., "1h 15m"
    tags = db.Column(db.String(255), nullable=True) # Comma-separated or could be a separate Tags table
    is_published = db.Column(db.Boolean, default=True)
    original_file_type = db.Column(db.String(50), nullable=True)  # Renamed from file_type, e.g., 'pdf', 'docx'
    file_size = db.Column(db.Integer, nullable=True)     # Size in bytes
    notes = db.Column(db.Text, nullable=True)

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # New relationship for this subtask (DocumentReview)
    reviews_received = db.relationship('DocumentReview', back_populates='document', lazy='dynamic', cascade="all, delete-orphan")

    # New relationships for this subtask (DocKeyword, DocumentAudio, DocumentSave, DocumentView)
    keywords = db.relationship('DocKeyword', back_populates='document', lazy='dynamic', cascade="all, delete-orphan")
    audio_files = db.relationship('DocumentAudio', back_populates='document', lazy='dynamic', cascade="all, delete-orphan")
    saves = db.relationship('DocumentSave', back_populates='document', lazy='dynamic', cascade="all, delete-orphan")
    views = db.relationship('DocumentView', back_populates='document', lazy='dynamic', cascade="all, delete-orphan")

    def __repr__(self):
        return f'<Document {self.id}: {self.title}>' # Added ID for clarity
