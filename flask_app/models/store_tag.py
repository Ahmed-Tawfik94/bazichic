from flask_app.extensions import db
from sqlalchemy import func

class StoreTag(db.Model):
    __tablename__ = 'store_tags'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(150), nullable=False, unique=True) # Assuming title should be unique for tags
    qcode = db.Column(db.String(50), nullable=True, unique=True, index=True) # Optional unique code
    enabled = db.Column(db.Boolean, default=True, nullable=False)

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # If tags can be associated with other models (e.g., Documents, Plans),
    # association tables and relationships would be defined here or in those models.
    # For now, it's a standalone tag definition.

    def __repr__(self):
        return f'<StoreTag {self.id}: {self.title}>'
