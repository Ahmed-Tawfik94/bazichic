from flask_app.extensions import db
from sqlalchemy import func

class DocumentType(db.Model):
    __tablename__ = 'document_types'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), unique=True, nullable=False)

    # Relationship back to Document (optional)
    documents = db.relationship('Document', backref='document_type', lazy='dynamic')

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    def __repr__(self):
        return f'<DocumentType {self.name}>'
