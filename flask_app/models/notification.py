from flask_app.extensions import db
from sqlalchemy import func

class Notification(db.Model):
    __tablename__ = 'notifications'

    id = db.Column(db.Integer, primary_key=True)
    sender_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_notification_sender_id', use_alter=True), nullable=True) # Nullable if system-sent

    title = db.Column(db.String(255), nullable=False)
    message = db.Column(db.Text, nullable=False)
    seen = db.Column(db.Boolean, default=False, nullable=False) # General seen status

    target_type = db.Column(db.String(50), nullable=True) # e.g., 'document', 'subscription', 'user'
    target_id = db.Column(db.Integer, nullable=True) # ID of the target entity

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationship to User (sender)
    sender = db.relationship('User', back_populates='notifications_sent', foreign_keys=[sender_id])

    # Polymorphic association for target (optional, can also be handled in application logic)
    # This requires a bit more setup if using SQLAlchemy's polymorphic features directly.
    # For now, target_type and target_id are informational.

    def __repr__(self):
        return f'<Notification {self.id}: {self.title}>'
