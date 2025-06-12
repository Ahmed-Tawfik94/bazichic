from flask_app.extensions import db  # Import db from extensions

class Plan(db.Model):
    __tablename__ = 'plans'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), unique=True, nullable=False) # e.g., Basic, Premium, Pro

    stripe_product_id = db.Column(db.String(120), unique=True, nullable=True) # Nullable if not all plans are on Stripe
    stripe_price_id = db.Column(db.String(120), unique=True, nullable=True)   # Nullable if not all plans are on Stripe

    price = db.Column(db.Numeric(10, 2), nullable=False) # e.g., 9.99
    interval = db.Column(db.String(50), nullable=False) # e.g., 'month', 'year'
    currency = db.Column(db.String(10), default='USD', nullable=False) # Added currency

    description = db.Column(db.Text, nullable=True) # Renamed from features, and made nullable

    is_active = db.Column(db.Boolean, default=True) # To easily deactivate plans
    is_available = db.Column(db.Boolean, default=True) # Added is_available

    # Relationship to Subscriptions
    # subscriptions = db.relationship('Subscription', backref='plan', lazy='dynamic')

    def __repr__(self):
        return f'<Plan {self.name}>'
