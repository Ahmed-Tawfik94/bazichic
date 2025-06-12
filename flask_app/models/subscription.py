from flask_app.extensions import db  # Import db from extensions
from sqlalchemy import func # For default timestamps

class Subscription(db.Model):
    __tablename__ = 'subscriptions'

    id = db.Column(db.Integer, primary_key=True)

    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False, index=True)
    user = db.relationship('User', backref=db.backref('subscriptions', lazy='dynamic', cascade="all, delete-orphan"))

    plan_id = db.Column(db.Integer, db.ForeignKey('plans.id'), nullable=False, index=True)
    plan = db.relationship('Plan', backref=db.backref('subscriptions', lazy='dynamic'))

    stripe_subscription_id = db.Column(db.String(120), unique=True, nullable=True, index=True) # Nullable if subscription not managed by Stripe
    stripe_customer_id = db.Column(db.String(120), nullable=True, index=True) # Store Stripe customer ID for user

    status = db.Column(db.String(50), nullable=False) # e.g., 'active', 'canceled', 'past_due', 'trialing'

    start_date = db.Column(db.DateTime, nullable=False, default=func.now())
    end_date = db.Column(db.DateTime, nullable=True) # Nullable for ongoing subscriptions
    current_period_start = db.Column(db.DateTime, nullable=True)
    current_period_end = db.Column(db.DateTime, nullable=True)

    trial_start_date = db.Column(db.DateTime, nullable=True)
    trial_end_date = db.Column(db.DateTime, nullable=True)

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    # Relationship to Invoices
    # This uses the Subscription.stripe_subscription_id to link to Invoice.stripe_subscription_id
    invoices = db.relationship('Invoice',
                               foreign_keys='Invoice.stripe_subscription_id',
                               primaryjoin='Subscription.stripe_subscription_id == Invoice.stripe_subscription_id',
                               back_populates='subscription',
                               lazy='dynamic',
                               cascade="all, delete-orphan")

    def __repr__(self):
        return f'<Subscription {self.id} for User {self.user_id} to Plan {self.plan_id}>'
