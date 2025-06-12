from flask_app.extensions import db
from sqlalchemy import func

class Referral(db.Model):
    __tablename__ = 'referrals'

    id = db.Column(db.Integer, primary_key=True)

    referrer_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_referral_referrer_id'), nullable=False, index=True)
    referred_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_referral_referred_id'), nullable=False, unique=True, index=True) # Assuming one user can only be referred once

    referral_code_used = db.Column(db.String(50), nullable=False, index=True)
    points_awarded_to_referrer = db.Column(db.Integer, nullable=True) # Points awarded for this specific referral action
    status = db.Column(db.String(50), default='pending', nullable=False) # e.g., 'pending', 'approved', 'rejected'

    # Link to the specific RewardPoint entry created for this referral
    reward_point_id = db.Column(db.Integer, db.ForeignKey('reward_points.id', name='fk_referral_reward_point_id'), unique=True, nullable=True)

    created_at = db.Column(db.DateTime, default=func.now())

    # Relationships
    referrer = db.relationship('User', foreign_keys=[referrer_id], back_populates='referrals_made')
    referred_user = db.relationship('User', foreign_keys=[referred_id], back_populates='referred_by_relations') # This matches the User.referred_by_relations

    # One-to-one relationship with RewardPoint
    reward_point_awarded = db.relationship('RewardPoint', uselist=False, backref=db.backref('referral_source', uselist=False))


    def __repr__(self):
        return f'<Referral {self.id}: Referrer {self.referrer_id} referred {self.referred_id} using {self.referral_code_used}>'
