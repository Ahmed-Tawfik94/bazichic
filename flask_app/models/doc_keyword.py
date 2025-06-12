from flask_app.extensions import db
from sqlalchemy import func

class DocKeyword(db.Model):
    __tablename__ = 'doc_keywords'

    id = db.Column(db.Integer, primary_key=True)
    document_id = db.Column(db.Integer, db.ForeignKey('documents.id', name='fk_dockeyword_document_id'), nullable=False, index=True)
    keyword = db.Column(db.String(100), nullable=False, index=True) # Indexing keyword might be useful

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationship to Document
    document = db.relationship('Document', back_populates='keywords')

    def __repr__(self):
        return f'<DocKeyword {self.keyword} for Document {self.document_id}>'
