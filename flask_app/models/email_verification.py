from flask_app.extensions import db
from sqlalchemy import func
import secrets # For generating secure tokens
from datetime import datetime, timedelta

class EmailVerification(db.Model):
    __tablename__ = 'email_verifications'

    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_emailverification_user_id'), nullable=False, index=True)

    token = db.Column(db.String(128), unique=True, nullable=False, index=True)
    expires_at = db.Column(db.DateTime, nullable=False)
    verified_at = db.Column(db.DateTime, nullable=True) # Timestamp when token was used

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationship to User
    user = db.relationship('User', back_populates='email_verifications')

    def __init__(self, user_id, expires_in_hours=24):
        self.user_id = user_id
        self.token = secrets.token_urlsafe(64) # Generate a secure random token
        self.expires_at = datetime.utcnow() + timedelta(hours=expires_in_hours)

    def is_expired(self):
        return datetime.utcnow() > self.expires_at

    def is_verified(self):
        return self.verified_at is not None

    def __repr__(self):
        return f'<EmailVerification for User {self.user_id} - Verified: {self.is_verified()}>'
