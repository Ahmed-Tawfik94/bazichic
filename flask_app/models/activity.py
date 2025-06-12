from flask_app.extensions import db
from sqlalchemy import func

class Activity(db.Model):
    __tablename__ = 'activities'

    id = db.Column(db.Integer, primary_key=True)
    who_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_activity_who_id', use_alter=True), nullable=True) # Nullable for system activities

    title = db.Column(db.String(255), nullable=False)
    message = db.Column(db.Text, nullable=False)

    target_type = db.Column(db.String(100), nullable=True) # Corresponds to data_title in PHP (e.g., 'document', 'user')
    target_id = db.Column(db.String(255), nullable=True)   # Corresponds to data_id in PHP (can be int or string like qcode)

    seen = db.Column(db.Boolean, default=False, nullable=False)

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationship to User (who performed the activity)
    user = db.relationship('User', back_populates='activities_performed', foreign_keys=[who_id])

    def __repr__(self):
        return f'<Activity {self.id}: {self.title} by User {self.who_id}>'
