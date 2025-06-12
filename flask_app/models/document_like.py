from flask_app.extensions import db
from sqlalchemy import func, UniqueConstraint

class DocumentLike(db.Model):
    __tablename__ = 'document_likes'

    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_documentlike_user_id'), nullable=False, index=True)
    document_id = db.Column(db.Integer, db.ForeignKey('documents.id', name='fk_documentlike_document_id'), nullable=False, index=True)

    created_at = db.Column(db.DateTime, default=func.now())

    # Relationships
    user = db.relationship('User', back_populates='document_likes')
    document = db.relationship('Document', backref=db.backref('likes', lazy='dynamic', cascade="all, delete-orphan"))

    # Unique constraint for user_id and document_id
    __table_args__ = (UniqueConstraint('user_id', 'document_id', name='uq_user_document_like'),)

    def __repr__(self):
        return f'<DocumentLike User {self.user_id} likes Document {self.document_id}>'
