from flask_app.extensions import db
from sqlalchemy import func # For default timestamps

class Category(db.Model):
    __tablename__ = 'categories'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(150), nullable=False)
    description = db.Column(db.Text, nullable=True)
    qcode = db.Column(db.String(50), unique=True, nullable=True, index=True)
    is_published = db.Column(db.Boolean, default=True)
    magazine_only = db.Column(db.Boolean, default=False)
    taxonomy = db.Column(db.String(100), nullable=True) # e.g., 'genre', 'topic'
    image_path = db.Column(db.String(255), nullable=True)

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationship to Documents
    documents = db.relationship('Document', backref='category', lazy='dynamic')

    def __repr__(self):
        return f'<Category {self.title}>'
