from flask_app.extensions import db
from sqlalchemy import func

class Status(db.Model):
    __tablename__ = 'status' # Singular as per existing table in PHP schema

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), unique=True, nullable=False) # e.g., "active", "pending_approval", "suspended"

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationship back to User (optional, if you want to see all users with a status)
    # users_with_status = db.relationship('User', back_populates='status_info', lazy='dynamic')

    def __repr__(self):
        return f'<Status {self.id}: {self.name}>'
