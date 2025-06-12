from flask_app.extensions import db
from sqlalchemy import func

class FreeTrial(db.Model):
    __tablename__ = 'free_trials'

    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_freetrial_user_id'), nullable=False, index=True)
    plan_id = db.Column(db.Integer, db.ForeignKey('plans.id', name='fk_freetrial_plan_id'), nullable=False, index=True)

    status = db.Column(db.String(50), nullable=True, default='active') # e.g., 'active', 'expired', 'canceled'
    expires_at = db.Column(db.DateTime, nullable=False) # Mapped from 'date_expiring'

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationships
    user = db.relationship('User', back_populates='free_trials_taken')
    plan = db.relationship('Plan', back_populates='free_trials_on_plan')

    def __repr__(self):
        return f'<FreeTrial {self.id} for User {self.user_id} on Plan {self.plan_id}, Expires: {self.expires_at}>'
