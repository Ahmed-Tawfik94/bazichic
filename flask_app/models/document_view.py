from flask_app.extensions import db
from sqlalchemy import func

class DocumentView(db.Model):
    __tablename__ = 'document_views'

    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_documentview_user_id'), nullable=False, index=True) # Assuming views are by logged-in users
    document_id = db.Column(db.Integer, db.ForeignKey('documents.id', name='fk_documentview_document_id'), nullable=False, index=True)

    # Additional fields like IP address, duration, etc., could be added if needed.
    # For now, just a record of a view event.

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now()) # Useful if views can be updated (e.g. view duration)

    # Relationships
    user = db.relationship('User', back_populates='document_views')
    document = db.relationship('Document', back_populates='views')

    def __repr__(self):
        return f'<DocumentView User {self.user_id} viewed Document {self.document_id} at {self.created_at}>'
