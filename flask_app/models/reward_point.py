from flask_app.extensions import db
from sqlalchemy import func

class RewardPoint(db.Model):
    __tablename__ = 'reward_points'

    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_rewardpoint_user_id'), nullable=False, index=True)

    points = db.Column(db.Integer, nullable=False)
    transaction_type = db.Column(db.String(50), nullable=False) # e.g., 'referral_bonus', 'signup_bonus', 'redeemed'
    status = db.Column(db.String(50), default='active', nullable=False) # e.g., 'pending', 'active', 'expired', 'used'
    referral_code_used = db.Column(db.String(50), nullable=True, index=True) # Which referral code led to these points

    created_at = db.Column(db.DateTime, default=func.now())

    # Relationship to User
    user = db.relationship('User', back_populates='reward_points_earned')

    # Relationship to Referral (one-to-one, if a reward point is directly tied to one referral action)
    # This is defined via back_populates in the Referral model for the one-to-one

    def __repr__(self):
        return f'<RewardPoint {self.id} for User {self.user_id}: {self.points} points, Type: {self.transaction_type}>'
