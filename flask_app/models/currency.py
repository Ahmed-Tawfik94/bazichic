from flask_app.extensions import db
from sqlalchemy import func

class Currency(db.Model):
    __tablename__ = 'currencies'

    id = db.Column(db.Integer, primary_key=True)
    currency_code = db.Column(db.String(10), unique=True, nullable=False, index=True) # e.g., "USD", "EUR"
    symbol = db.Column(db.String(5), nullable=False) # e.g., "$", "€"
    name = db.Column(db.String(50), nullable=True) # e.g., "US Dollar", "Euro"

    created_at = db.Column(db.DateTime, default=func.now())
    updated_at = db.Column(db.DateTime, default=func.now(), onupdate=func.now())

    def __repr__(self):
        return f'<Currency {self.currency_code} ({self.symbol})>'
