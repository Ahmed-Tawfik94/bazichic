from flask_app.extensions import db
from sqlalchemy import func

class Payment(db.Model):
    __tablename__ = 'transaction_details' # Explicit table name

    id = db.Column(db.Integer, primary_key=True)

    item_code_plan_id = db.Column(db.Integer, db.ForeignKey('plans.id'), nullable=False, index=True) # FK to plans.id
    sender_user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False, index=True) # FK to users.id

    amount = db.Column(db.Float, nullable=False) # Or db.Numeric for fixed precision
    item_description = db.Column(db.String(255), nullable=True)
    status = db.Column(db.String(50), nullable=False) # e.g., 'pending', 'completed', 'failed', 'refunded'
    currency = db.Column(db.String(10), nullable=False, default='USD')
    txn_id = db.Column(db.String(120), unique=True, nullable=False, index=True) # Transaction ID from payment gateway

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationships
    user = db.relationship('User', backref=db.backref('payments', lazy='dynamic', foreign_keys=[sender_user_id]))
    plan = db.relationship('Plan', backref=db.backref('payments', lazy='dynamic', foreign_keys=[item_code_plan_id]))

    def __repr__(self):
        return f'<Payment {self.txn_id} - Amount: {self.amount} {self.currency}>'
