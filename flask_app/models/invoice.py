from flask_app.extensions import db
from sqlalchemy import func

class Invoice(db.Model):
    __tablename__ = 'invoice' # Singular as per migration/original table name

    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id', name='fk_invoice_user_id'), nullable=False, index=True)

    stripe_invoice_id = db.Column(db.String(120), unique=True, nullable=False, index=True) # Mapped from 'invoice_id' in migration
    stripe_subscription_id = db.Column(db.String(120), db.ForeignKey('subscriptions.stripe_subscription_id', name='fk_invoice_stripe_subscription_id'), nullable=True, index=True) # Mapped from 'subscription_id'

    amount_paid = db.Column(db.Float, nullable=False) # Assuming this is in cents if from Stripe, or float if already converted
    status = db.Column(db.String(50), nullable=False) # e.g., 'paid', 'open', 'void', 'uncollectible'
    paid_at = db.Column(db.DateTime, nullable=True) # Timestamp when payment was made

    created_at = db.Column(db.DateTime, default=func.now()) # When the invoice record was created in our system

    # Relationships
    user = db.relationship('User', back_populates='invoices')
    # Relationship to Subscription model via stripe_subscription_id
    subscription = db.relationship('Subscription',
                                   foreign_keys=[stripe_subscription_id],
                                   primaryjoin='Invoice.stripe_subscription_id == Subscription.stripe_subscription_id',
                                   back_populates='invoices',
                                   uselist=False) # Should be one subscription per invoice via this ID

    def __repr__(self):
        return f'<Invoice {self.id} - Stripe ID: {self.stripe_invoice_id}, User: {self.user_id}, Status: {self.status}>'
