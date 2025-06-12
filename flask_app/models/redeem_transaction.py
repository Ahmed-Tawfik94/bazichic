from flask_app.extensions import db
from sqlalchemy import func

class RedeemTransaction(db.Model):
    __tablename__ = 'redeem_transactions'

    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_redeemtransaction_user_id'), nullable=False, index=True)

    points_redeemed = db.Column(db.Integer, nullable=False)
    type = db.Column(db.String(50), nullable=False) # e.g., 'withdraw_money', 'coupon', 'gift_card'
    status = db.Column(db.String(50), default='pending', nullable=False) # e.g., 'pending', 'approved', 'rejected', 'completed'

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now()) # Added updated_at

    # Relationship to User
    user = db.relationship('User', back_populates='redeem_transactions_made')

    def __repr__(self):
        return f'<RedeemTransaction {self.id} for User {self.user_id}: {self.points_redeemed} points, Type: {self.type}, Status: {self.status}>'
