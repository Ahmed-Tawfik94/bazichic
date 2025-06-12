from flask_app.extensions import db
from sqlalchemy import func, UniqueConstraint

class DocumentSave(db.Model):
    __tablename__ = 'document_saves'

    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_documentsave_user_id'), nullable=False, index=True)
    document_id = db.Column(db.Integer, db.ForeignKey('documents.id', name='fk_documentsave_document_id'), nullable=False, index=True)

    page = db.Column(db.Integer, nullable=True) # Current page number
    progress = db.Column(db.Integer, nullable=True) # E.g., percentage completion (0-100)

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationships
    user = db.relationship('User', back_populates='document_saves')
    document = db.relationship('Document', back_populates='saves')

    # Unique constraint for user_id and document_id
    __table_args__ = (UniqueConstraint('user_id', 'document_id', name='uq_user_document_save'),)

    def __repr__(self):
        return f'<DocumentSave User {self.user_id} for Document {self.document_id} at page {self.page} ({self.progress}%)>'
