from flask_app.extensions import db
from sqlalchemy import func, UniqueConstraint

class DocumentReview(db.Model):
    __tablename__ = 'document_reviews'

    id = db.Column(db.Integer, primary_key=True)
    document_id = db.Column(db.Integer, db.ForeignKey('documents.id', name='fk_review_document_id'), nullable=False, index=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_review_user_id'), nullable=False, index=True)

    stars = db.Column(db.Integer, nullable=False) # Assuming 1-5 stars
    text = db.Column(db.Text, nullable=True)

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationships
    document = db.relationship('Document', back_populates='reviews_received')
    user = db.relationship('User', back_populates='document_reviews_written')

    # Unique constraint for user_id and document_id
    __table_args__ = (UniqueConstraint('user_id', 'document_id', name='uq_user_document_review'),)

    def __repr__(self):
        return f'<DocumentReview User {self.user_id} for Document {self.document_id}: {self.stars} stars>'
